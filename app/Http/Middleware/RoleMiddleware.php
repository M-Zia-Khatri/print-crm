<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $requiredRole = UserRole::tryFrom($role);
        $userRole = $request->user()?->role;

        if (! $requiredRole || ! $userRole instanceof UserRole || ! $userRole->isRole($requiredRole)) {
            abort(403);
        }

        return $next($request);
    }
}
