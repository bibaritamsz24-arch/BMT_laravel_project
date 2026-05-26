<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\UserAccount;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StudentWelcomeController extends Controller
{
    /**
     * Display the student welcome page.
     */
    public function __invoke(): View
    {
        /** @var UserAccount $studentAccount */
        $studentAccount = auth('student')->user();
        $studentAccount->loadMissing('student.degree');

        return view('student.welcome', compact('studentAccount'));
    }

    public function profile(Request $request): JsonResponse
    {
        /** @var UserAccount $studentAccount */
        $studentAccount = $request->user('student');
        $studentAccount->loadMissing('student.degree');

        return response()->json([
            'student' => $this->studentJson($studentAccount),
        ]);
    }

    public function updateProfile(Request $request): JsonResponse
    {
        /** @var UserAccount $studentAccount */
        $studentAccount = $request->user('student');
        $studentAccount->loadMissing('student');
        $student = $studentAccount->student;

        abort_unless($student instanceof Student, 404);

        $request->merge([
            'first_name' => $this->normalizeName($request->input('first_name')),
            'middle_name' => $this->normalizeName($request->input('middle_name')),
            'last_name' => $this->normalizeName($request->input('last_name')),
            'username' => strtolower(trim((string) $request->input('username'))),
            'address' => trim((string) $request->input('address')),
            'contact' => preg_replace('/\D+/', '', (string) $request->input('contact')),
            'email' => strtolower(trim((string) $request->input('email'))),
        ]);

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'min:2', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'min:2', 'max:100'],
            'address' => ['required', 'string', 'min:5', 'max:1000'],
            'contact' => ['required', 'digits:11'],
            'username' => [
                'required',
                'string',
                'min:4',
                'max:50',
                'regex:/^[A-Za-z0-9._-]+$/',
                Rule::unique('user_accounts', 'username')->ignore($studentAccount->id),
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('students', 'email')->ignore($student->id),
                Rule::unique('user_accounts', 'email')->ignore($studentAccount->id),
                Rule::unique('users', 'email'),
            ],
        ], [
            'username.regex' => 'The username may only contain letters, numbers, dots, underscores, and hyphens.',
        ], [
            'contact' => 'contact number',
        ]);

        DB::transaction(function () use ($student, $studentAccount, $validated): void {
            $studentAccount->update([
                'username' => $validated['username'],
                'email' => $validated['email'],
            ]);

            $student->update([
                'first_name' => $validated['first_name'],
                'middle_name' => $validated['middle_name'] ?? null,
                'last_name' => $validated['last_name'],
                'address' => $validated['address'],
                'contact' => $validated['contact'],
                'email' => $validated['email'],
            ]);
        });

        return response()->json([
            'message' => 'Student profile updated successfully.',
            'account' => $this->studentJson($studentAccount->fresh()->load('student.degree')),
        ]);
    }

    private function studentJson(UserAccount $studentAccount): array
    {
        $studentAccount->loadMissing('student.degree');
        $student = $studentAccount->student;

        return [
            'type' => 'student',
            'full_name' => $student?->full_name ?: $studentAccount->username,
            'first_name' => $student?->first_name,
            'middle_name' => $student?->middle_name,
            'last_name' => $student?->last_name,
            'username' => $studentAccount->username,
            'email' => $studentAccount->email,
            'contact' => $student?->contact ?: 'Not set',
            'address' => $student?->address ?: 'Not set',
            'degree' => $student?->degree?->title ?: 'No degree assigned',
            'role' => 'student',
            'status' => $studentAccount->is_active ? 'Active' : 'Inactive',
        ];
    }

    private function normalizeName(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $normalized = trim((string) $value);

        return $normalized === '' ? null : $normalized;
    }
}
