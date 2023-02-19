<?php

namespace App\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('pagination')]
final class PaginationComponent
{
    public int $page = 1;

    public int $pages = 1;

    public array $path = [
        'pageParam' => '',
        'params' => null,
        'route' => ''
    ];
}
