<?php

namespace Kolette\Auth\Enums;

use BenSampo\Enum\Enum;

final class Role extends Enum
{
    const SUPER_ADMIN = 'SUPER_ADMIN';

    const ADMIN = 'ADMIN';

    const USER = 'USER';

   /// const MERCHANT = 'MERCHANT';

    const CUSTOMER = 'CUSTOMER';

    const GUEST = 'GUEST';

    const READER = 'READER';
    
    public static function getAdminRoles(): array
    {
        return [
            Role::ADMIN,
            Role::READER
        ];
    }

    /**
     * The default role for all new added user
     */
    public static function default(): string
    {
        return Role::USER;
    }

    public static function defaultPermissions(): array
    {
        return [
            // Adding "ALL" will give all permission
            Role::ADMIN => ['ALL'],

            Role::USER => []
        ];
    }

    public static function getPermissions(string $role): array
    {
        return Role::defaultPermissions()[$role] ?? [];
    }
}
