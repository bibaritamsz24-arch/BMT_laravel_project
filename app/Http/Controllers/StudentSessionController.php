<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentLoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class StudentSessionController extends Controller
{
    /**
     * Send old student login links to the unified login page.
     */
    public function create(): RedirectResponse
    {
        return redirect()->route('login');
    }

    /**
     * Handle a student login request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(StudentLoginRequest $request): RedirectResponse
    {
        $credentials = $request->validated();

        if (! Auth::guard('student')->attempt([
            'username' => $credentials['username'],
            'password' => $credentials['password'],
            'is_active' => 1,
        ])) {
            throw ValidationException::withMessages([
                'username' => 'Incorrect username or password.',
            ]);
        }

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

    /**
     * Destroy an authenticated student session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('student')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('success', 'You have been logged out.');
    }
}
