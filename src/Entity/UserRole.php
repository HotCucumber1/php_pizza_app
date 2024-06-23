<?php

namespace App\Entity;

class UserRole
{
    public const USER = 'ROLE_USER';
    public const ADMIN = 'ROLE_ADMIN';

    public static function isValidRole(string $role): bool
    {
        return $role === self::USER || $role === self::ADMIN;
    }
}