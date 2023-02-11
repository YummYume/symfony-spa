<?php

namespace App\Components\Table\Row;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('checkbox', 'components/table/row/checkbox.html.twig')]
final class CheckboxComponent
{
    public ?array $config = null;
}
