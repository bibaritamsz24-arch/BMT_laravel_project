<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SessionUserAccount
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = null;

        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
        }

        if (Auth::guard('student')->check()) {
            $user = Auth::guard('student')->user();
        }

        if (! $user) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login');
        }

        Session::put('logged_id', $user->id);
        Session::put('logged_user', $user->username ?? $user->name);
        Session::put('logged_role', $user->role);

        if ($user->role === 'student' && $user->must_change_password && ! $request->routeIs('student.password.*')) {
            Session::put('pending_password_change_user_id', $user->id);

            return redirect()
                ->route('student.password.edit')
                ->with('message', 'You must change your password before accessing the dashboard.');
        }

        return $next($request);
    }
}
