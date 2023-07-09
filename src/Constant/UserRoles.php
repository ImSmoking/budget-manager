<?php

declare(strict_types=1);

namespace App\Constant;

enum UserRoles
{
    final public const USER = 'ROLE_USER';
    final public const ADMIN = 'ROLE_ADMIN';
    final public const DEFAULT_ROLES = [self::USER];
    final public const ADMIN_ROLES = [self::USER, self::ADMIN];
    final public const ROLES_ARRAY = [self::USER, self::ADMIN];
}
