<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login form.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        if (! $request->filled('login')) {
            $request->merge([
                'login' => $request->input('email', $request->input('username')),
            ]);
        }

        $validated = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $login = $validated['login'];
        $password = $validated['password'];
        $remember = $request->boolean('remember');

        $webAttempted = filter_var($login, FILTER_VALIDATE_EMAIL)
            ? Auth::guard('web')->attempt([
                'email' => $login,
                'password' => $password,
            ], $remember)
            : Auth::guard('web')->attempt([
                'username' => $login,
                'password' => $password,
            ], $remember);

        if ($webAttempted) {
            $request->session()->regenerate();

            if (in_array($request->user()?->role, ['admin', 'teacher'], true)) {
                $request->session()->put([
                    'logged_id' => $request->user()->id,
                    'logged_user' => $request->user()->name,
                    'logged_role' => $request->user()->role,
                ]);

                return redirect()
                    ->route($this->dashboardRouteFor($request->user()))
                    ->with('success', 'Successful login.');
            }

            Auth::guard('web')->logout();
        }

        if (Auth::guard('student')->attempt([
            'username' => $login,
            'password' => $password,
            'is_active' => 1,
        ], $remember) || Auth::guard('student')->attempt([
            'email' => $login,
            'password' => $password,
            'is_active' => 1,
        ], $remember)) {
            $request->session()->regenerate();
            $request->session()->forget('student_password_changed');

            $student = Auth::guard('student')->user();

            $request->session()->put([
                'logged_id' => $student?->id,
                'logged_user' => $student?->username,
                'logged_role' => $student?->role,
            ]);

            return redirect()
                ->route('student.password.edit')
                ->with('success', 'Student login successful. Please change your password first.');
        }

        throw ValidationException::withMessages([
            'login' => __('Incorrect credentials.'),
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        Auth::guard('student')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Get the dashboard route for the authenticated web user.
     */
    private function dashboardRouteFor(?object $user): string
    {
        return match ($user?->role) {
            'teacher' => 'teacher.dashboard',
            'admin' => 'admin.dashboard',
            default => 'login',
        };
    }
}
