<?php

namespace App\Enums;

enum UserRole: string
{
    case SUPER_ADMIN = 'super_admin';
    case ADMIN = 'admin';
    case VIEWER = 'viewer';

    /**
     * @return array<int, string>
     */
    public static function roles(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function isRole(self|string $role): bool
    {
        return $this === ($role instanceof self ? $role : self::tryFrom($role));
    }
}
