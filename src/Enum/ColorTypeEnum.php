<?php

namespace App\Enum;

use App\Enum\Traits\UtilsTrait;

enum ColorTypeEnum: string
{
    use UtilsTrait;

    case ERROR = 'error';
    case INFO = 'info';
    case PRIMARY = 'primary';
    case SECONDARY = 'secondary';
    case SUCCESS = 'success';
    case WARNING = 'warning';
}
