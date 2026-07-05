<?php

namespace App\Http\Controllers\Users;

use App\Actions\Users\CreateUser;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Users/Create', [
            'roles' => [UserRole::ADMIN->value, UserRole::VIEWER->value],
        ]);
    }

    public function store(StoreUserRequest $request, CreateUser $createUser): RedirectResponse
    {
        $createUser->handle($request->validated());

        return to_route('dashboard')->with('success', 'User created successfully.');
    }
}
