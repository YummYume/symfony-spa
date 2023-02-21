<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class TableComponentExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('table_parse_option', [$this, 'parseOption']),
            new TwigFunction('table_parse_options', [$this, 'parseOptions']),
        ];
    }

    public function parseOption(mixed $option, mixed ...$parameters): mixed
    {
        return \is_callable($option) ? $option(...$parameters) : $option;
    }

    public function parseOptions(array $options, mixed ...$parameters): array
    {
        return array_map(fn (mixed $option): mixed => $this->parseOption($option, ...$parameters), $options);
    }
}
