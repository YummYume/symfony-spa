<?php

namespace App\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('icon')]
class IconComponent
{
    public string $class = 'h-6 w-6h-6 w-6';

    public ?string $icon = null;
}
