<?php

namespace Tests\Feature\Users;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CreateUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_access_create_page(): void
    {
        $this->actingAs($this->user(UserRole::SUPER_ADMIN))->get(route('users.create'))->assertOk();
    }

    public function test_admin_user_cannot_access_create_page(): void
    {
        $this->actingAs($this->user(UserRole::ADMIN))->get(route('users.create'))->assertForbidden();
    }

    public function test_viewer_user_cannot_access_create_page(): void
    {
        $this->actingAs($this->user(UserRole::VIEWER))->get(route('users.create'))->assertForbidden();
    }

    public function test_super_admin_can_create_admin_user_with_valid_data(): void
    {
        $this->actingAs($this->user(UserRole::SUPER_ADMIN))->post(route('users.store'), $this->payload(['role' => UserRole::ADMIN->value]))->assertRedirect(route('dashboard', absolute: false));

        $this->assertDatabaseHas('users', ['username' => 'newuser', 'role' => UserRole::ADMIN->value]);
    }

    public function test_super_admin_can_create_viewer_user_with_valid_data(): void
    {
        $this->actingAs($this->user(UserRole::SUPER_ADMIN))->post(route('users.store'), $this->payload(['role' => UserRole::VIEWER->value]))->assertRedirect(route('dashboard', absolute: false));

        $this->assertDatabaseHas('users', ['username' => 'newuser', 'role' => UserRole::VIEWER->value]);
    }

    public function test_super_admin_cannot_create_super_admin_user_from_ui(): void
    {
        $this->actingAs($this->user(UserRole::SUPER_ADMIN))->post(route('users.store'), $this->payload(['role' => UserRole::SUPER_ADMIN->value]))->assertSessionHasErrors('role');
    }

    public function test_duplicate_username_rejected(): void
    {
        User::factory()->create(['username' => 'newuser']);

        $this->actingAs($this->user(UserRole::SUPER_ADMIN))->post(route('users.store'), $this->payload())->assertSessionHasErrors('username');
    }

    public function test_password_less_than_eight_chars_rejected(): void
    {
        $this->actingAs($this->user(UserRole::SUPER_ADMIN))->post(route('users.store'), $this->payload(['password' => 'short']))->assertSessionHasErrors('password');
    }

    public function test_missing_role_rejected(): void
    {
        $payload = $this->payload();
        unset($payload['role']);

        $this->actingAs($this->user(UserRole::SUPER_ADMIN))->post(route('users.store'), $payload)->assertSessionHasErrors('role');
    }


    public function test_username_is_trimmed_and_lowercased_before_create(): void
    {
        $this->actingAs($this->user(UserRole::SUPER_ADMIN))->post(route('users.store'), $this->payload(['username' => '  Mixed_User  ']))->assertRedirect(route('dashboard', absolute: false));

        $this->assertDatabaseHas('users', ['username' => 'mixed_user']);
    }

    public function test_user_is_created_and_password_is_hashed(): void
    {
        $this->actingAs($this->user(UserRole::SUPER_ADMIN))->post(route('users.store'), $this->payload());

        $user = User::query()->where('username', 'newuser')->firstOrFail();
        $this->assertTrue(Hash::check('secret123', $user->password));
    }

    public function test_flash_message_shows_on_successful_create(): void
    {
        $this->actingAs($this->user(UserRole::SUPER_ADMIN))->post(route('users.store'), $this->payload())->assertSessionHas('success', 'User created successfully.');
    }

    private function user(UserRole $role): User
    {
        return User::factory()->create(['role' => $role]);
    }

    /** @param array<string, string> $overrides */
    private function payload(array $overrides = []): array
    {
        return array_merge(['username' => 'newuser', 'password' => 'secret123', 'role' => UserRole::VIEWER->value], $overrides);
    }
}
