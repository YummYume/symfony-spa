<?php

namespace App\Components;

use App\Enum\ColorTypeEnum;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('button')]
final class ButtonComponent
{
    public const LEFT = 'left';
    public const RIGHT = 'right';

    public bool $centered = true;
    public bool $circle = false;
    public bool $ghost = false;
    public bool $square = false;
    public bool $submit = false;
    public string $class = '';
    public string $content = '';
    public ?string $icon = null;
    public string $iconPosition = self::RIGHT;
    public string $variant = ColorTypeEnum::PRIMARY->value;
}
