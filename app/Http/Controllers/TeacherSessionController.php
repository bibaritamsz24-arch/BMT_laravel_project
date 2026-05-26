<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class TeacherSessionController extends Controller
{
    /**
     * Send old teacher login links to the unified login page.
     */
    public function create(): RedirectResponse
    {
        return redirect()->route('login');
    }

    /**
     * Handle a teacher login request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'Incorrect teacher credentials.',
            ]);
        }

        $request->session()->regenerate();

        if ($request->user()?->role !== 'teacher') {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => 'Use the correct portal for this account.',
            ]);
        }

        $request->session()->put([
            'logged_id' => $request->user()->id,
            'logged_user' => $request->user()->name,
            'logged_role' => $request->user()->role,
        ]);

        return redirect()
            ->route('teacher.dashboard')
            ->with('success', 'Teacher login successful.');
    }
}
