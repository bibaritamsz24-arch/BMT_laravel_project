<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateStudentRequest;
use App\Models\Degree;
use App\Models\Student;
use App\Models\User;
use App\Models\UserAccount;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Throwable;
use Illuminate\View\View;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|JsonResponse
    {
        $studentsQuery = Student::query()
            ->with('degree')
            ->with('userAccount');

        if ($request->wantsJson()) {
            return response()->json([
                'students' => $studentsQuery
                    ->get()
                    ->map(fn (Student $student): array => $this->studentJson($student))
                    ->values(),
            ]);
        }

        $students = $studentsQuery->paginate(2);

        $degrees = Degree::query()
            ->orderBy('title')
            ->get();

        return view('students.index', compact('students', 'degrees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $degrees = Degree::query()
            ->orderBy('title')
            ->get();

        return view('students.create', compact('degrees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $request->validate([
            'degree_id' => 'required|exists:degrees,id',
            'email' => 'required|email|unique:students,email|unique:user_accounts,email',
            'username' => 'required|unique:user_accounts,username',
            'password' => 'required|min:8',
            'first_name' => 'required|string|min:2|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'required|string|min:2|max:100',
            'address' => 'required|string|min:5|max:1000',
            'contact' => 'required|digits:11',
        ]);

        $user = UserAccount::create([
            'email' => $request->input('email'),
            'username' => $request->input('username'),
            'password' => Hash::make($request->input('password')),
            'role' => 'student',
            'must_change_password' => true,
        ]);

        $student = Student::create([
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
            'address' => $request->input('address'),
            'contact' => $request->input('contact'),
            'email' => $request->input('email'),
            'degree_id' => $request->input('degree_id'),
            'user_account_id' => $user->id,
        ]);

        $msg = 'New student added!';
        Log::info($msg);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Student added successfully.',
                'student' => $student,
            ], 201);
        }

        return redirect()->route('students.index')
            ->with('success', 'Student added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Student $student): View|JsonResponse
    {
        $student->load('degree', 'userAccount');

        if ($request->wantsJson()) {
            return response()->json([
                'student' => $this->studentJson($student),
            ]);
        }

        return view('students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Student $student): View|JsonResponse
    {
        $degrees = Degree::query()
            ->orderBy('title')
            ->get();

        if ($request->wantsJson()) {
            return response()->json([
                'student' => $this->studentJson($student->load('degree', 'userAccount')),
                'degrees' => $degrees,
            ]);
        }

        return view('students.edit', compact('student', 'degrees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudentRequest $request, Student $student): RedirectResponse|JsonResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($student, $validated): void {
            $userAccount = $student->userAccount()->first();

            $userAccountAttributes = $this->userAccountAttributes(
                $validated,
                includePassword: filled($validated['password'] ?? null),
            );

            if ($userAccount) {
                $userAccount->update($userAccountAttributes);
            } else {
                $userAccount = UserAccount::create($this->userAccountAttributes($validated));
            }

            $student->update($this->studentAttributes($validated, $userAccount));
        });

        $this->logActivity('student updated');

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Student updated successfully.',
                'account' => $this->studentJson($student->fresh()->load('degree', 'userAccount')),
            ]);
        }

        return redirect()->route('students.index')
            ->with('success', 'Student updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Student $student): RedirectResponse|JsonResponse
    {
        $this->logActivity('student deleted');

        DB::transaction(function () use ($student): void {
            $loginEmail = $student->email;
            $student->loadMissing('userAccount');
            $userAccount = $student->userAccount;

            $student->delete();

            User::query()
                ->where('email', $loginEmail)
                ->where('role', 'student')
                ->delete();

            if ($userAccount && $userAccount->exists) {
                $userAccount->delete();
            }
        });

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Student deleted successfully.',
            ]);
        }

        return redirect()->route('students.index')
            ->with('success', 'Student deleted successfully.');
    }

    /**
     * Build the student payload from validated form data.
     *
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    private function studentAttributes(array $validated, ?UserAccount $userAccount = null): array
    {
        return [
            'user_account_id' => $userAccount?->id,
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'] ?? null,
            'last_name' => $validated['last_name'],
            'address' => $validated['address'],
            'contact' => $validated['contact'],
            'email' => $validated['email'],
            'degree_id' => $validated['degree_id'],
        ];
    }

    /**
     * Build the mirrored user account payload stored in user_accounts.
     *
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    private function userAccountAttributes(array $validated, bool $includePassword = true): array
    {
        $attributes = [
            'username' => $validated['username'],
            'email' => $validated['email'],
            'role' => 'student',
            'is_active' => 1,
        ];

        if ($includePassword && isset($validated['password'])) {
            $attributes['password'] = $validated['password'];
            $attributes['must_change_password'] = true;
        }

        return $attributes;
    }

    /**
     * Write a best-effort activity log without breaking the request.
     */
    private function logActivity(string $message): void
    {
        try {
            Log::info($message);
        } catch (Throwable) {
            // Ignore logging failures so CRUD and login flows still work.
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function studentJson(Student $student): array
    {
        $student->loadMissing('degree', 'userAccount');

        return [
            'id' => $student->id,
            'type' => 'student',
            'full_name' => $student->full_name,
            'first_name' => $student->first_name,
            'middle_name' => $student->middle_name,
            'last_name' => $student->last_name,
            'username' => $student->userAccount->username,
            'email' => $student->email,
            'contact' => $student->contact,
            'address' => $student->address,
            'degree_id' => $student->degree_id,
            'degree' => $student->degree->title,
            'role' => 'student',
            'status' => $student->userAccount->is_active ? 'Active' : 'Inactive',
            'view_url' => route('students.show', $student),
            'edit_url' => route('students.edit', $student),
            'update_url' => route('students.update', $student),
            'delete_url' => route('students.destroy', $student),
        ];
    }
}
