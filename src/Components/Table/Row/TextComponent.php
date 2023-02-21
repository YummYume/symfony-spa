<?php

namespace App\Components\Table\Row;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('text', 'components/table/row/text.html.twig')]
final class TextComponent
{
    public ?array $config = null;
}
