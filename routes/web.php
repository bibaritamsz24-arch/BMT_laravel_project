<?php

use App\Http\Controllers\DegreeController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentPasswordController;
use App\Http\Controllers\StudentSessionController;
use App\Http\Controllers\StudentWelcomeController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\TeacherSessionController;
use App\Http\Controllers\UserController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

Route::get('/', function (): RedirectResponse {
    if (Auth::guard('student')->check()) {
        return (bool) request()->session()->get('student_password_changed', false)
            ? redirect()->route('student.welcome')
            : redirect()->route('student.password.edit');
    }

    if (! Auth::check()) {
        return redirect()->route('login');
    }

    return match (Auth::user()?->role) {
        'teacher' => redirect()->route('teacher.dashboard'),
        'admin' => redirect()->route('admin.dashboard'),
        default => redirect()->route('login'),
    };
})->name('home');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [UserController::class, 'login'])->name('login');
    Route::post('/login', [UserController::class, 'authenticate']);
    Route::redirect('/portal', '/login')->name('portal.login');
});

Route::prefix('student')->name('student.')->group(function (): void {
    Route::middleware('guest:student')->group(function (): void {
        Route::get('/login', [StudentSessionController::class, 'create'])->name('login');
        Route::post('/login', [StudentSessionController::class, 'store'])->name('login.store');
    });

    Route::middleware(['auth:student', 'session.user.account'])->group(function (): void {
        Route::post('/logout', [StudentSessionController::class, 'destroy'])->name('logout');
        Route::get('/password', [StudentPasswordController::class, 'edit'])->name('password.edit');
        Route::put('/password', [StudentPasswordController::class, 'update'])->name('password.update');
        Route::middleware('student.password.changed')->group(function (): void {
            Route::get('/dashboard', StudentWelcomeController::class)->name('dashboard');
            Route::get('/welcome', StudentWelcomeController::class)->name('welcome');
            Route::get('/profile', [StudentWelcomeController::class, 'profile'])->name('profile.show');
            Route::put('/profile', [StudentWelcomeController::class, 'updateProfile'])->name('profile.update');
        });
    });
});

Route::prefix('teacher')->name('teacher.')->group(function (): void {
    Route::middleware('guest')->group(function (): void {
        Route::get('/login', [TeacherSessionController::class, 'create'])->name('login');
        Route::post('/login', [TeacherSessionController::class, 'store'])->name('login.store');
    });
});

Route::middleware(['auth:student', 'session.user.account', 'student.password.changed'])
    ->get('/welcome-page', StudentWelcomeController::class)
    ->name('student.welcome-page');

Route::view('/maintenance', 'maintenance')->name('maintenance');

Route::middleware(['auth', 'session.user.account'])->group(function (): void {
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');

    Route::get('/password', function () {
        return view('auth.password');
    })->name('password.edit');

    Route::put('/password', function (Request $request): RedirectResponse {
        $validated = $request->validate([
            'current_password' => ['required', 'string', 'current_password:web'],
            'password' => ['required', 'string', 'min:8', 'confirmed', 'different:current_password'],
        ], [
            'current_password.current_password' => 'Your old password is incorrect.',
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('password.edit')
            ->with('success', 'Password updated successfully.');
    })->name('password.update');

    Route::middleware('middleware.one')->group(function (): void {
        Route::get('/admin', [UserController::class, 'adminDashboard'])->name('admin.dashboard');

        Route::resource('degrees', DegreeController::class);
        Route::resource('students', StudentController::class);
        Route::get('/teachers', [TeacherController::class, 'index'])->name('teachers.index');
        Route::get('/teachers/create', [TeacherController::class, 'create'])->name('teachers.create');
        Route::post('/teachers', [TeacherController::class, 'store'])->name('teachers.store');
        Route::get('/teachers/{teacher}', [TeacherController::class, 'show'])->name('teachers.show');
        Route::get('/teachers/{teacher}/edit', [TeacherController::class, 'edit'])->name('teachers.edit');
        Route::put('/teachers/{teacher}', [TeacherController::class, 'update'])->name('teachers.update');
        Route::delete('/teachers/{teacher}', [TeacherController::class, 'destroy'])->name('teachers.destroy');
    });

    Route::middleware('middleware.two')->group(function (): void {
        Route::get('/teacher/profile', [TeacherController::class, 'profile'])->name('teacher.profile.show');
        Route::put('/teacher/profile', [TeacherController::class, 'updateProfile'])->name('teacher.profile.update');
        Route::get('/teacher', function () {
            return view('teacher.dashboard');
        })->name('teacher.dashboard');
    });
});
