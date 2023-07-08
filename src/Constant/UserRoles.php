<?php

declare(strict_types=1);

namespace App\Constant;

enum UserRoles
{
    final public const USER = 'ROLE_USER';
    final public const ADMIN = 'ROLE_ADMIN';
    final public const DEFAULT_ROLE = self::USER;
    final public const ROLES_ARRAY = [self::USER, self::ADMIN];
}
