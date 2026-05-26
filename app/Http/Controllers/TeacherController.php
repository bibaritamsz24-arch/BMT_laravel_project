<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TeacherController extends Controller
{
    public function profile(Request $request): JsonResponse
    {
        /** @var User $teacher */
        $teacher = $request->user();
        $this->ensureTeacher($teacher);

        return response()->json([
            'teacher' => $this->teacherJson($teacher),
        ]);
    }

    public function updateProfile(Request $request): JsonResponse
    {
        /** @var User $teacher */
        $teacher = $request->user();
        $this->ensureTeacher($teacher);

        $validated = $this->validatedTeacher($request, $teacher);

        $teacher->update($this->teacherAttributes(
            $validated,
            includePassword: filled($validated['password'] ?? null),
        ));

        return response()->json([
            'message' => 'Teacher profile updated successfully.',
            'account' => $this->teacherJson($teacher->fresh()),
        ]);
    }

    /**
     * Display teacher accounts.
     */
    public function index(Request $request): View|JsonResponse
    {
        $teachers = User::query()
            ->where('role', 'teacher')
            ->orderBy('name')
            ->paginate(10);

        if ($request->wantsJson()) {
            return response()->json([
                'teachers' => $teachers->getCollection()
                    ->map(fn (User $teacher): array => $this->teacherJson($teacher))
                    ->values(),
            ]);
        }

        return view('teachers.index', compact('teachers'));
    }

    /**
     * Show the teacher account form.
     */
    public function create(): View
    {
        return view('teachers.create');
    }

    /**
     * Store a teacher login account.
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $this->validatedTeacher($request);

        $teacher = User::query()->create($this->teacherAttributes($validated));

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Teacher account created successfully.',
                'account' => $this->teacherJson($teacher),
            ], 201);
        }

        return redirect()->route('teachers.index')
            ->with('success', 'Teacher account created successfully.');
    }

    /**
     * Display a teacher account.
     */
    public function show(Request $request, User $teacher): View|JsonResponse
    {
        $this->ensureTeacher($teacher);

        if ($request->wantsJson()) {
            return response()->json([
                'teacher' => $this->teacherJson($teacher),
            ]);
        }

        return view('teachers.show', compact('teacher'));
    }

    /**
     * Show the teacher edit form.
     */
    public function edit(Request $request, User $teacher): View|JsonResponse
    {
        $this->ensureTeacher($teacher);

        if ($request->wantsJson()) {
            return response()->json([
                'teacher' => $this->teacherJson($teacher),
            ]);
        }

        return view('teachers.edit', compact('teacher'));
    }

    /**
     * Update a teacher account.
     */
    public function update(Request $request, User $teacher): RedirectResponse|JsonResponse
    {
        $this->ensureTeacher($teacher);

        $validated = $this->validatedTeacher($request, $teacher);

        $teacher->update($this->teacherAttributes(
            $validated,
            includePassword: filled($validated['password'] ?? null),
        ));

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Teacher account updated successfully.',
                'account' => $this->teacherJson($teacher->fresh()),
            ]);
        }

        return redirect()->route('teachers.index')
            ->with('success', 'Teacher account updated successfully.');
    }

    /**
     * Delete a teacher account.
     */
    public function destroy(Request $request, User $teacher): RedirectResponse|JsonResponse
    {
        $this->ensureTeacher($teacher);

        $teacher->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Teacher account deleted successfully.',
            ]);
        }

        return redirect()->route('teachers.index')
            ->with('success', 'Teacher account deleted successfully.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedTeacher(Request $request, ?User $teacher = null): array
    {
        $request->merge([
            'first_name' => $this->normalizeName($request->input('first_name', $request->input('name'))),
            'middle_name' => $this->normalizeName($request->input('middle_name')),
            'last_name' => $this->normalizeName($request->input('last_name')),
            'address' => trim((string) $request->input('address')),
            'username' => strtolower(trim((string) $request->input('username'))),
            'contact' => preg_replace('/\D+/', '', (string) $request->input('contact')),
            'email' => strtolower(trim((string) $request->input('email'))),
        ]);

        return $request->validate([
            'first_name' => ['required', 'string', 'min:2', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'min:2', 'max:100'],
            'address' => ['required', 'string', 'min:5', 'max:1000'],
            'username' => [
                'required',
                'string',
                'min:4',
                'max:50',
                'regex:/^[A-Za-z0-9._-]+$/',
                Rule::unique('users', 'username')->ignore($teacher?->id),
                Rule::unique('user_accounts', 'username'),
            ],
            'contact' => ['required', 'digits:11'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($teacher?->id),
                Rule::unique('user_accounts', 'email'),
            ],
            'password' => [$teacher ? 'nullable' : 'required', 'string', 'min:8', 'confirmed'],
        ], [
            'username.regex' => 'The username may only contain letters, numbers, dots, underscores, and hyphens.',
        ], [
            'first_name' => 'first name',
            'middle_name' => 'middle name',
            'last_name' => 'last name',
            'address' => 'address',
            'contact' => 'contact number',
            'password_confirmation' => 'password confirmation',
        ]);
    }

    /**
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    private function teacherAttributes(array $validated, bool $includePassword = true): array
    {
        $attributes = [
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'] ?? null,
            'last_name' => $validated['last_name'],
            'address' => $validated['address'],
            'name' => trim(implode(' ', array_filter([
                $validated['first_name'],
                $validated['middle_name'] ?? null,
                $validated['last_name'],
            ]))),
            'username' => $validated['username'],
            'email' => $validated['email'],
            'contact' => $validated['contact'],
            'role' => 'teacher',
        ];

        if ($includePassword && filled($validated['password'] ?? null)) {
            $attributes['password'] = $validated['password'];
        }

        return $attributes;
    }

    private function ensureTeacher(User $teacher): void
    {
        abort_unless($teacher->role === 'teacher', 404);
    }

    private function normalizeName(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $normalized = trim((string) $value);

        return $normalized === '' ? null : $normalized;
    }

    /**
     * @return array<string, mixed>
     */
    private function teacherJson(User $teacher): array
    {
        return [
            'id' => $teacher->id,
            'type' => 'teacher',
            'full_name' => $teacher->name,
            'first_name' => $teacher->first_name ?: $teacher->name,
            'middle_name' => $teacher->middle_name,
            'last_name' => $teacher->last_name,
            'username' => $teacher->username ?: str($teacher->email)->before('@')->toString(),
            'email' => $teacher->email,
            'contact' => $teacher->contact ?: 'Not set',
            'address' => $teacher->address ?: 'Not set',
            'role' => 'teacher',
            'status' => 'Active',
            'view_url' => route('teachers.show', $teacher),
            'edit_url' => route('teachers.edit', $teacher),
            'update_url' => route('teachers.update', $teacher),
            'delete_url' => route('teachers.destroy', $teacher),
        ];
    }
}
