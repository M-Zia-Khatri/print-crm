<?php

namespace Tests\Unit\Http\Requests;

use App\Enums\UserRole;
use App\Http\Requests\User\StoreUserRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class StoreUserRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_username_is_required_unique_and_max_255(): void
    {
        User::factory()->create(['username' => 'taken']);

        $this->assertFalse($this->validator(['username' => ''])->passes());
        $this->assertFalse($this->validator(['username' => 'taken'])->passes());
        $this->assertFalse($this->validator(['username' => str_repeat('a', 256)])->passes());
        $this->assertTrue($this->validator(['username' => str_repeat('a', 255)])->passes());
    }


    public function test_username_is_normalized_before_validation(): void
    {
        $request = StoreUserRequest::create('/users', 'POST', [
            'username' => '  Mixed_User  ',
            'password' => 'secret123',
            'role' => UserRole::VIEWER->value,
        ]);

        $request->setContainer($this->app);
        $request->validateResolved();

        $this->assertSame('mixed_user', $request->validated('username'));
    }

    public function test_password_is_required_and_min_eight(): void
    {
        $this->assertFalse($this->validator(['password' => ''])->passes());
        $this->assertFalse($this->validator(['password' => 'short'])->passes());
        $this->assertTrue($this->validator(['password' => 'secret123'])->passes());
    }

    public function test_role_is_required_and_must_be_admin_or_viewer(): void
    {
        $this->assertFalse($this->validator(['role' => ''])->passes());
        $this->assertFalse($this->validator(['role' => UserRole::SUPER_ADMIN->value])->passes());
        $this->assertTrue($this->validator(['role' => UserRole::ADMIN->value])->passes());
        $this->assertTrue($this->validator(['role' => UserRole::VIEWER->value])->passes());
    }

    /** @param array<string, string> $overrides */
    private function validator(array $overrides)
    {
        $request = new StoreUserRequest;

        return Validator::make(array_merge([
            'username' => 'validuser',
            'password' => 'secret123',
            'role' => UserRole::VIEWER->value,
        ], $overrides), $request->rules());
    }
}
