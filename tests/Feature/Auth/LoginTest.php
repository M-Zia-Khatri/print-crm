<?php

namespace Tests\Feature\Auth;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_with_correct_username_password_and_role_succeeds(): void
    {
        $user = User::factory()->create(['username' => 'jane', 'role' => UserRole::ADMIN]);

        $response = $this->post(route('login.store'), ['username' => 'jane', 'password' => 'password', 'role' => UserRole::ADMIN->value]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_login_with_wrong_role_fails(): void
    {
        User::factory()->create(['username' => 'jane', 'role' => UserRole::ADMIN]);

        $this->post(route('login.store'), ['username' => 'jane', 'password' => 'password', 'role' => UserRole::VIEWER->value]);

        $this->assertGuest();
    }

    public function test_login_with_invalid_username_fails(): void
    {
        $this->post(route('login.store'), ['username' => 'missing', 'password' => 'password', 'role' => UserRole::ADMIN->value]);

        $this->assertGuest();
    }

    public function test_login_with_invalid_password_fails(): void
    {
        User::factory()->create(['username' => 'jane', 'role' => UserRole::ADMIN]);

        $this->post(route('login.store'), ['username' => 'jane', 'password' => 'wrong-password', 'role' => UserRole::ADMIN->value]);

        $this->assertGuest();
    }

    public function test_authenticated_user_can_access_dashboard(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get(route('dashboard'))->assertOk();
    }
}
