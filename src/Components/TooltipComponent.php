<?php

namespace App\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('tooltip')]
final class TooltipComponent
{
    public string $text = '';
}
