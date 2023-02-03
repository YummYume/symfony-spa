<?php

namespace App\Components;

use App\Entity\User;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('avatar')]
class AvatarComponent
{
    public ?User $user;
}
