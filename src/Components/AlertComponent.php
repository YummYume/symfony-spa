<?php

namespace App\Components;

use App\Enum\ColorTypeEnum;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('alert')]
final class AlertComponent
{
    public string $type = ColorTypeEnum::Error->value;

    public string $message = '';
}
