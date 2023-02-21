<?php

namespace App\Components\Table;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('table', 'components/table/table.html.twig')]
final class TableComponent
{
    public ?array $config = null;
}
