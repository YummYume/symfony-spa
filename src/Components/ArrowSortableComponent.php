<?php

namespace App\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('arrow_sortable', 'components/arrow_sortable.html.twig')]
final class ArrowSortableComponent
{
   public string $sortable = "sortable";
}
