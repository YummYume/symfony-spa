<?php

namespace App\Enum\Traits;

trait UtilsTrait
{
    public static function toArray(bool $reversed = false): array
    {
        return array_combine(
            array_map(static fn ($item): string|int => $reversed ? $item->value : $item->name, self::cases()),
            array_map(static fn ($item): string|int => $reversed ? $item->name : $item->value, self::cases())
        );
    }

    public static function random(): self
    {
        return self::cases()[array_rand(self::cases())];
    }
}
