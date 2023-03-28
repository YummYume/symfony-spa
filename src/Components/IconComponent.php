<?php

namespace App\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('icon')]
class IconComponent
{
    public string $class = '';
    public ?string $icon = null;
}
