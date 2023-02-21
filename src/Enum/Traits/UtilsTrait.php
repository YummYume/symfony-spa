<?php

namespace App\Enum\Traits;

trait UtilsTrait
{
    public static function toArray(bool $reversed = false, array $without = []): array
    {
        $cases = array_filter(self::cases(), static fn (self $role): bool => !\in_array($role, $without, true));

        return array_combine(
            array_map(static fn ($item): string|int => $reversed ? $item->value : $item->name, $cases),
            array_map(static fn ($item): string|int => $reversed ? $item->name : $item->value, $cases)
        );
    }

    public static function random(): self
    {
        return self::cases()[array_rand(self::cases())];
    }
}
