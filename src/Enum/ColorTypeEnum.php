<?php

namespace App\Enum;

use App\Enum\Traits\UtilsTrait;

enum ColorTypeEnum: string
{
    use UtilsTrait;

    case Error = 'error';
    case Info = 'info';
    case Primary = 'primary';
    case Secondary = 'secondary';
    case Success = 'success';
    case Warning = 'warning';
}
