<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateStudentPortalPasswordRequest;
use App\Models\User;
use App\Models\UserAccount;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StudentPasswordController extends Controller
{
    /**
     * Display the student password update form.
     */
    public function edit(): View
    {
        return view('student.auth.password');
    }

    /**
     * Update the authenticated student's password.
     */
    public function update(UpdateStudentPortalPasswordRequest $request): RedirectResponse
    {
        /** @var UserAccount $studentAccount */
        $studentAccount = $request->user('student');
        $validated = $request->validated();

        $studentAccount->loadMissing('student');

        DB::transaction(function () use ($studentAccount, $validated): void {
            $studentAccount->update([
                'password' => $validated['password'],
                'must_change_password' => false,
            ]);

            User::query()->updateOrCreate(
                ['email' => $studentAccount->email],
                [
                    'name' => $studentAccount->student?->full_name ?: $studentAccount->username,
                    'password' => $validated['password'],
                ],
            );
        });

        $request->session()->put('student_password_changed', true);

        return redirect()->route('student.welcome')
            ->with('success', 'Password changed successfully. Welcome.');
    }
}
