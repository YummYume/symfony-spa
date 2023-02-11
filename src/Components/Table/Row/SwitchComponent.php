<?php

namespace App\Components\Table\Row;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('switch', 'components/table/row/switch.html.twig')]
final class SwitchComponent
{
    public ?array $config = null;
}
