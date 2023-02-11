<?php

namespace App\Components;

use App\Entity\User;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('avatar')]
final class AvatarComponent
{
    public ?User $user = null;

    public ?string $id = null;

    public bool $small = true;

    public bool $permanent = false;
}
