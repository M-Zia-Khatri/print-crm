<?php

namespace App\Actions\Users;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateUser
{
    /**
     * @param  array{username: string, password: string, role: string}  $data
     */
    public function handle(array $data): User
    {
        return User::query()->create([
            'name' => $data['username'],
            'email' => $data['username'].'@print-crm.local',
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
            'email_verified_at' => now(),
        ]);
    }
}
