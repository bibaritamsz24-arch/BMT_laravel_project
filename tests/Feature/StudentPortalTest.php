<?php

namespace Tests\Feature;

use App\Models\Degree;
use App\Models\Student;
use App\Models\User;
use App\Models\UserAccount;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class StudentPortalTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_log_in_with_username_and_is_redirected_to_change_password(): void
    {
        $studentAccount = $this->createStudentAccount();

        $response = $this->post(route('login'), [
            'login' => $studentAccount->username,
            'password' => 'studentpass',
        ]);

        $response->assertRedirect(route('student.password.edit'));
        $response->assertSessionHas('success', 'Student login successful. Please change your password first.');
        $this->assertAuthenticated('student');

        $this->get(route('student.password.edit'))
            ->assertOk()
            ->assertSeeText('Update your student password');
    }

    public function test_student_sees_error_message_when_login_credentials_are_invalid(): void
    {
        $studentAccount = $this->createStudentAccount();

        $response = $this->from(route('login'))->post(route('login'), [
            'login' => $studentAccount->username,
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors([
            'login' => 'Incorrect credentials.',
        ]);
        $this->assertGuest('student');

        $this->get(route('login'))
            ->assertOk()
            ->assertSeeText('Incorrect credentials.');
    }

    public function test_guest_is_redirected_to_main_login_when_opening_student_change_password_page(): void
    {
        $response = $this->get(route('student.password.edit'));

        $response->assertRedirect(route('login'));
    }

    public function test_student_cannot_change_password_with_wrong_current_password(): void
    {
        $studentAccount = $this->createStudentAccount();

        $response = $this->actingAs($studentAccount, 'student')
            ->from(route('student.password.edit'))
            ->put(route('student.password.update'), [
                'current_password' => 'wrong-password',
                'password' => 'new-student-pass',
                'password_confirmation' => 'new-student-pass',
            ]);

        $response->assertRedirect(route('student.password.edit'));
        $response->assertSessionHasErrors([
            'current_password' => 'Wrong password.',
        ]);

        $studentAccount->refresh();

        $this->assertTrue(Hash::check('studentpass', $studentAccount->password));
    }

    public function test_student_cannot_change_password_when_confirmation_does_not_match(): void
    {
        $studentAccount = $this->createStudentAccount();

        $response = $this->actingAs($studentAccount, 'student')
            ->from(route('student.password.edit'))
            ->put(route('student.password.update'), [
                'current_password' => 'studentpass',
                'password' => 'new-student-pass',
                'password_confirmation' => 'different-password',
            ]);

        $response->assertRedirect(route('student.password.edit'));
        $response->assertSessionHasErrors([
            'password' => 'Password not match.',
        ]);

        $this->get(route('student.password.edit'))
            ->assertOk()
            ->assertSeeText('Password not match.');
    }

    public function test_student_can_change_password_and_is_redirected_to_welcome(): void
    {
        $studentAccount = $this->createStudentAccount();

        $response = $this->actingAs($studentAccount, 'student')
            ->put(route('student.password.update'), [
                'current_password' => 'studentpass',
                'password' => 'new-student-pass',
                'password_confirmation' => 'new-student-pass',
            ]);

        $response->assertRedirect(route('student.welcome'));
        $response->assertSessionHas('success', 'Password changed successfully. Welcome.');
        $this->assertAuthenticated('student');

        $studentAccount->refresh();
        $user = User::query()->where('email', $studentAccount->email)->firstOrFail();

        $this->assertTrue(Hash::check('new-student-pass', $studentAccount->password));
        $this->assertFalse($studentAccount->must_change_password);
        $this->assertTrue(Hash::check('new-student-pass', $user->password));

        $this->get(route('student.welcome'))
            ->assertOk()
            ->assertSeeText('Welcome to student dashboard');
    }

    public function test_student_must_change_password_before_opening_welcome_page_directly(): void
    {
        $studentAccount = $this->createStudentAccount();

        $this->actingAs($studentAccount, 'student')
            ->get(route('student.welcome-page'))
            ->assertRedirect(route('student.password.edit'));
    }

    public function test_student_login_after_password_change_still_goes_to_change_password_page(): void
    {
        $studentAccount = $this->createStudentAccount();

        $this->actingAs($studentAccount, 'student')
            ->put(route('student.password.update'), [
                'current_password' => 'studentpass',
                'password' => 'new-student-pass',
                'password_confirmation' => 'new-student-pass',
            ])
            ->assertRedirect(route('student.welcome'));

        $this->post(route('student.logout'));

        $response = $this->post(route('login'), [
            'login' => $studentAccount->username,
            'password' => 'new-student-pass',
        ]);

        $response->assertRedirect(route('student.password.edit'));

        $this->get(route('student.password.edit'))
            ->assertOk()
            ->assertSeeText('Update your student password')
            ->assertSeeText($studentAccount->display_name);
    }

    private function createStudentAccount(): UserAccount
    {
        $degree = Degree::query()->create([
            'title' => 'BS Information Technology',
        ]);

        $studentAccount = UserAccount::query()->create([
            'username' => 'beverly.tamayo',
            'email' => 'beverly@example.com',
            'password' => 'studentpass',
            'role' => 'student',
            'is_active' => 1,
        ]);

        Student::query()->create([
            'user_account_id' => $studentAccount->id,
            'first_name' => 'Beverly',
            'middle_name' => null,
            'last_name' => 'Tamayo',
            'address' => '123 Student Avenue, Manila',
            'contact' => '09123456789',
            'email' => 'beverly@example.com',
            'degree_id' => $degree->id,
        ]);

        return $studentAccount;
    }
}
