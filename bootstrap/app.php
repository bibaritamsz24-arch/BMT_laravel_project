<?php

use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Auth;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectTo(
            guests: function (Request $request): string {
                return route('login');
            },
            users: function (Request $request): string {
                if (Auth::guard('student')->check()) {
                    return $request->session()->get('student_password_changed', false)
                        ? route('student.welcome')
                        : route('student.password.edit');
                }

                return match (Auth::user()?->role) {
                    'teacher' => route('teacher.dashboard'),
                    'admin' => route('admin.dashboard'),
                    default => route('login'),
                };
            },
        );

        $middleware->alias([
            'session.user.account' => \App\Http\Middleware\SessionUserAccount::class,
            'middleware.one' => \App\Http\Middleware\MiddlewareOne::class,
            'middleware.two' => \App\Http\Middleware\MiddlewareTwo::class,
            'under.maintenance' => \App\Http\Middleware\DownForMaintenance::class,
            'student.password.changed' => \App\Http\Middleware\EnsureStudentPasswordIsChanged::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
