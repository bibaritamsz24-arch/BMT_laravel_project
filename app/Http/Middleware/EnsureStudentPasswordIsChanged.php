<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureStudentPasswordIsChanged
{
    public function handle(Request $request, Closure $next): Response
    {
        $student = $request->user('student');

        if ($student && ! (bool) $request->session()->get('student_password_changed', false)) {
            return redirect()->route('student.password.edit');
        }

        return $next($request);
    }
}
