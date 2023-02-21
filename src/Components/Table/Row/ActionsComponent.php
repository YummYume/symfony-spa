<?php

namespace App\Components\Table\Row;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('actions', 'components/table/row/actions.html.twig')]
final class ActionsComponent
{
    public ?array $config = null;
}
