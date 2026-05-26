<?php

namespace App\Http\Controllers;

use App\Models\Degree;
use App\Models\User;
use App\Models\UserAccount;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Show the main login page.
     */
    public function login(): View
    {
        return view('auth.login');
    }

    /**
     * Authenticate admin, teacher, or student accounts.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(Request $request): RedirectResponse
    {
        $request->merge([
            'username' => $request->input('username', $request->input('login', $request->input('email'))),
        ]);

        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $user_name = $credentials['username'];
        $password = $credentials['password'];
        $remember = $request->boolean('remember');

        $user = User::query()
            ->where('email', $user_name)
            ->orWhere('username', $user_name)
            ->first();

        if ($user && Hash::check($password, $user->password)) {
            Auth::guard('web')->login($user, $remember);
            $request->session()->regenerate();

            if (in_array($user?->role, ['admin', 'teacher'], true)) {
                Session::put('logged_id', $user->id);
                Session::put('logged_user', $user->username ?: $user->name);
                Session::put('logged_role', $user->role);

                return $this->redirectToDashboard($user->role)
                    ->with('success', 'Successful login.');
            }

            Auth::guard('web')->logout();
        }

        $student = UserAccount::query()
            ->where('is_active', 1)
            ->where(function ($query) use ($user_name): void {
                $query->where('username', $user_name)
                    ->orWhere('email', $user_name);
            })
            ->first();

        if ($student && Hash::check($password, $student->password)) {
            Auth::guard('student')->login($student, $remember);
            $request->session()->regenerate();
            $request->session()->forget('student_password_changed');

            Session::put('logged_id', $student->id);
            Session::put('logged_user', $student->username);
            Session::put('logged_role', $student->role);

            return redirect()
                ->route('student.password.edit')
                ->with('success', 'Student login successful. Please change your password first.');
        }

        throw ValidationException::withMessages([
            'login' => __('Incorrect credentials.'),
        ]);
    }

    /**
     * Display the admin user accounts dashboard.
     */
    public function adminDashboard(Request $request): View|JsonResponse
    {
        $webAccounts = User::query()
            ->whereIn('role', ['admin', 'teacher'])
            ->orderBy('role')
            ->orderBy('name')
            ->get()
            ->map(fn (User $user): array => [
                'full_name' => $user->name,
                'username' => $user->username ?: str($user->email)->before('@')->toString(),
                'email' => $user->email,
                'contact' => $user->contact ?: 'Not set',
                'role' => $user->role,
                'status' => 'Active',
                'type' => $user->role,
                'id' => $user->id,
                'address' => $user->address ?: 'Not set',
                'first_name' => $user->first_name ?: $user->name,
                'middle_name' => $user->middle_name,
                'last_name' => $user->last_name,
                'view_url' => $user->role === 'teacher' ? route('teachers.show', $user) : null,
                'edit_url' => $user->role === 'teacher' ? route('teachers.edit', $user) : null,
                'update_url' => $user->role === 'teacher' ? route('teachers.update', $user) : null,
                'delete_url' => $user->role === 'teacher' ? route('teachers.destroy', $user) : null,
            ]);

        $studentAccounts = UserAccount::query()
            ->with('student.degree')
            ->orderBy('username')
            ->get()
            ->map(fn (UserAccount $account): array => [
                'full_name' => $account->display_name,
                'username' => $account->username,
                'email' => $account->email,
                'contact' => $account->student?->contact ?: 'Not set',
                'role' => $account->role,
                'status' => $account->is_active ? 'Active' : 'Inactive',
                'type' => 'student',
                'id' => $account->student?->id,
                'address' => $account->student?->address ?: 'Not set',
                'degree' => $account->student?->degree?->title ?: 'No degree assigned',
                'degree_id' => $account->student?->degree_id,
                'first_name' => $account->student?->first_name,
                'middle_name' => $account->student?->middle_name,
                'last_name' => $account->student?->last_name,
                'view_url' => $account->student ? route('students.show', $account->student) : null,
                'edit_url' => $account->student ? route('students.edit', $account->student) : null,
                'update_url' => $account->student ? route('students.update', $account->student) : null,
                'delete_url' => $account->student ? route('students.destroy', $account->student) : null,
            ]);

        $accounts = $webAccounts
            ->merge($studentAccounts)
            ->sortBy([
                ['role', 'asc'],
                ['full_name', 'asc'],
            ])
            ->values();

        if ($request->wantsJson()) {
            return response()->json([
                'accounts' => $accounts,
            ]);
        }

        $degrees = Degree::query()
            ->orderBy('title')
            ->get();

        return view('admin.dashboard', compact('accounts', 'degrees'));
    }

    /**
     * Log out the current user.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        Auth::guard('student')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Redirect users based on their role.
     */
    private function redirectToDashboard(?string $role): RedirectResponse
    {
        if ($role === 'teacher') {
            return redirect()->route('teacher.dashboard');
        }

        if ($role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('login');
    }
}
