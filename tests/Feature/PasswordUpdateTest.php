<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PasswordUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_when_opening_password_page(): void
    {
        $response = $this->get(route('password.edit'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_update_password(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put(route('password.update'), [
            'current_password' => 'password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        $response->assertRedirect(route('password.edit'));
        $response->assertSessionHas('success', 'Password updated successfully.');

        $user->refresh();

        $this->assertTrue(Hash::check('new-password', $user->password));
    }

    public function test_authenticated_user_cannot_update_password_with_wrong_old_password(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->from(route('password.edit'))
            ->put(route('password.update'), [
                'current_password' => 'wrong-password',
                'password' => 'new-password',
                'password_confirmation' => 'new-password',
            ]);

        $response->assertRedirect(route('password.edit'));
        $response->assertSessionHasErrors([
            'current_password' => 'Your old password is incorrect.',
        ]);

        $user->refresh();

        $this->assertTrue(Hash::check('password', $user->password));
    }
}
