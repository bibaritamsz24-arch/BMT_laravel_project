<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_log_in_and_is_redirected_to_admin_dashboard(): void
    {
        $user = User::factory()->create();

        $this->assertStringStartsWith('$argon2id$', $user->password);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $response->assertSessionHas('success', 'Successful login.');
        $this->assertAuthenticatedAs($user);

        $this->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSeeText('Successful login.');
    }

    public function test_authenticated_user_is_redirected_to_admin_dashboard_from_home(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/');

        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_guest_is_redirected_to_login_when_opening_degrees(): void
    {
        $response = $this->get(route('degrees.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_guest_opening_students_is_redirected_to_login(): void
    {
        $response = $this->get(route('students.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_user_sees_incorrect_credentials_message_on_failed_login(): void
    {
        $user = User::factory()->create();

        $response = $this->from(route('login'))->post(route('login'), [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors([
            'login' => 'Incorrect credentials.',
        ]);
        $this->assertGuest();

        $this->get(route('login'))
            ->assertOk()
            ->assertSeeText('Incorrect credentials.');
    }

    public function test_user_with_legacy_bcrypt_password_can_log_in_and_is_rehashed_to_argon2id(): void
    {
        $user = User::factory()->create();

        DB::table('users')
            ->where('id', $user->id)
            ->update([
                'password' => Hash::driver('bcrypt')->make('password'),
            ]);

        $user->refresh();

        $this->assertStringStartsWith('$2y$', $user->password);

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($user);

        $user->refresh();

        $this->assertStringStartsWith('$argon2id$', $user->password);
        $this->assertTrue(Hash::check('password', $user->password));
    }

    public function test_user_can_log_out(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('logout'));

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
}
