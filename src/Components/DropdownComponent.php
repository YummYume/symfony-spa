<?php

namespace App\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('dropdown')]
final class DropdownComponent
{
    public bool $alignRight = true;
}
