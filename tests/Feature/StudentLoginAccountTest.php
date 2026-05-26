<?php

namespace Tests\Feature;

use App\Models\Degree;
use App\Models\Student;
use App\Models\User;
use App\Models\UserAccount;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class StudentLoginAccountTest extends TestCase
{
    use RefreshDatabase;

    public function test_creating_a_student_also_creates_a_login_account(): void
    {
        $admin = User::factory()->create();
        $degree = Degree::query()->create([
            'title' => 'BS Information Technology',
        ]);

        $response = $this->actingAs($admin)->post(route('students.store'), [
            'first_name' => 'Juan',
            'middle_name' => 'Santos',
            'last_name' => 'Dela Cruz',
            'address' => '123 Main Street, Manila',
            'contact' => '09123456789',
            'username' => 'juan.delacruz',
            'email' => 'juan@example.com',
            'degree_id' => $degree->id,
            'password' => 'studentpass',
            'password_confirmation' => 'studentpass',
        ]);

        $response->assertRedirect(route('student.password.edit'));
        $response->assertSessionHas('success', 'Student account created. You are now logged in as that student.');

        $this->assertDatabaseHas('students', [
            'email' => 'juan@example.com',
            'first_name' => 'Juan',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'juan@example.com',
            'name' => 'Juan Santos Dela Cruz',
            'role' => 'student',
        ]);

        $student = Student::query()->where('email', 'juan@example.com')->firstOrFail();
        $user = User::query()->where('email', 'juan@example.com')->firstOrFail();
        $userAccount = UserAccount::query()->findOrFail($student->user_account_id);

        $this->assertStringStartsWith('$argon2id$', $user->password);
        $this->assertSame('juan@example.com', $userAccount->email);
        $this->assertSame('juan.delacruz', $userAccount->username);
        $this->assertSame('student', $userAccount->role);
        $this->assertTrue($userAccount->must_change_password);
        $this->assertStringStartsWith('$argon2id$', $userAccount->password);
        $this->assertTrue(Hash::driver('argon2id')->check('studentpass', $userAccount->password));
        $this->assertAuthenticatedAs($userAccount, 'student');

        $this->post(route('student.logout'));

        $loginResponse = $this->post(route('student.login.store'), [
            'username' => 'juan.delacruz',
            'password' => 'studentpass',
        ]);

        $loginResponse->assertRedirect(route('student.password.edit'));
        $this->assertAuthenticated('student');
    }

    public function test_updating_a_student_syncs_the_login_email_and_password(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
        ]);
        $degree = Degree::query()->create([
            'title' => 'BS Computer Science',
        ]);
        $studentUser = User::query()->create([
            'name' => 'Maria Cruz',
            'email' => 'maria@example.com',
            'password' => 'old-password',
        ]);
        $student = Student::query()->create([
            'user_account_id' => null,
            'first_name' => 'Maria',
            'middle_name' => null,
            'last_name' => 'Cruz',
            'address' => '456 Rizal Street, Cebu',
            'contact' => '09987654321',
            'email' => 'maria@example.com',
            'degree_id' => $degree->id,
        ]);

        $response = $this->actingAs($admin)->put(route('students.update', $student), [
            'first_name' => 'Maria',
            'middle_name' => 'Lopez',
            'last_name' => 'Cruz',
            'address' => '456 Rizal Street, Cebu',
            'contact' => '09987654321',
            'username' => 'maria.lopez',
            'email' => 'maria.lopez@example.com',
            'degree_id' => $degree->id,
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        $response->assertRedirect(route('students.index'));
        $response->assertSessionHas('success', 'Student and login account updated successfully.');

        $student->refresh();
        $studentUser->refresh();
        $userAccount = UserAccount::query()->findOrFail($student->user_account_id);

        $this->assertSame('maria.lopez@example.com', $student->email);
        $this->assertSame('maria.lopez@example.com', $studentUser->email);
        $this->assertSame('Maria Lopez Cruz', $studentUser->name);
        $this->assertStringStartsWith('$argon2id$', $studentUser->password);
        $this->assertTrue(Hash::check('new-password', $studentUser->password));
        $this->assertSame('maria.lopez@example.com', $userAccount->email);
        $this->assertSame('maria.lopez', $userAccount->username);
        $this->assertStringStartsWith('$argon2id$', $userAccount->password);
        $this->assertTrue(Hash::driver('argon2id')->check('new-password', $userAccount->password));
    }
}
