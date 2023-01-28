<?php

namespace App\Enum;

use App\Enum\Traits\UtilsTrait;

enum UserRoleEnum: string
{
    use UtilsTrait;

    case SuperAdmin = 'ROLE_SUPER_ADMIN';
    case Admin = 'ROLE_ADMIN';
    case User = 'ROLE_USER';
    case AllowedToSwitch = 'ROLE_ALLOWED_TO_SWITCH';
}
