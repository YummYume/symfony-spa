<?php

namespace App\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('button')]
class ButtonComponent
{
    public bool $centered = true;
    public bool $circle = false;
    public bool $ghost = false;
    public bool $square = false;
    public bool $submit = false;
    public string $class = '';
    public string $content = '';
    public string $variant = 'primary';
}
