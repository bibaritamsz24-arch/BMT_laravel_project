<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RoleAccessControlTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_login_goes_to_admin_dashboard(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->post(route('login'), [
            'email' => $admin->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_teacher_login_goes_to_teacher_dashboard(): void
    {
        $teacher = User::factory()->create([
            'role' => 'teacher',
        ]);

        $response = $this->post(route('login'), [
            'email' => $teacher->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('teacher.dashboard'));
    }

    public function test_teacher_portal_login_goes_to_teacher_dashboard(): void
    {
        $teacher = User::factory()->create([
            'role' => 'teacher',
        ]);

        $response = $this->post(route('teacher.login.store'), [
            'email' => $teacher->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('teacher.dashboard'));
        $response->assertSessionHas('success', 'Teacher login successful.');
    }

    public function test_guest_opening_teacher_dashboard_goes_to_main_login(): void
    {
        $this->get(route('teacher.dashboard'))
            ->assertRedirect(route('login'));
    }

    public function test_admin_account_cannot_use_teacher_portal_login(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->from(route('teacher.login'))->post(route('teacher.login.store'), [
            'email' => $admin->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('teacher.login'));
        $response->assertSessionHasErrors([
            'email' => 'Use the correct portal for this account.',
        ]);
        $this->assertGuest();
    }

    public function test_teacher_dashboard_only_shows_welcome_content(): void
    {
        $teacher = User::factory()->create([
            'name' => 'Maria Santos',
            'role' => 'teacher',
        ]);

        $this->actingAs($teacher)
            ->get(route('teacher.dashboard'))
            ->assertOk()
            ->assertSeeText('Welcome, Maria Santos.')
            ->assertDontSeeText('Student Enrollments')
            ->assertDontSeeText('View Students & Courses');
    }

    public function test_teacher_cannot_open_admin_dashboard(): void
    {
        $teacher = User::factory()->create([
            'role' => 'teacher',
        ]);

        $this->actingAs($teacher)
            ->get(route('admin.dashboard'))
            ->assertRedirect(route('home'));
    }

    public function test_admin_can_create_teacher_account(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->post(route('teachers.store'), [
            'name' => 'Maria Santos',
            'email' => 'maria.teacher@example.com',
            'password' => 'teacherpass',
            'password_confirmation' => 'teacherpass',
        ]);

        $response->assertRedirect(route('teacher.dashboard'));
        $response->assertSessionHas('success', 'Teacher account created. You are now logged in as that teacher.');

        $teacher = User::query()->where('email', 'maria.teacher@example.com')->firstOrFail();

        $this->assertSame('teacher', $teacher->role);
        $this->assertTrue(Hash::check('teacherpass', $teacher->password));
        $this->assertAuthenticatedAs($teacher);
    }

    public function test_database_seeder_creates_teacher_account(): void
    {
        $this->seed(DatabaseSeeder::class);

        $teacher = User::query()->where('email', 'teacher@example.com')->firstOrFail();

        $this->assertSame('Teacher', $teacher->name);
        $this->assertSame('teacher', $teacher->role);
        $this->assertTrue(Hash::check('password', $teacher->password));
    }

    public function test_student_web_login_is_rejected(): void
    {
        $student = User::factory()->create([
            'role' => 'student',
        ]);

        $response = $this->from(route('login'))->post(route('login'), [
            'email' => $student->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors([
            'login' => 'Incorrect credentials.',
        ]);
        $this->assertGuest();
    }
}
