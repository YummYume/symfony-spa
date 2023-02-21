<?php

namespace App\Enum;

use App\Enum\Traits\UtilsTrait;

enum SearchTypeEnum: string
{
    use UtilsTrait;

    case Profiles = 'profiles';

    public static function getSearchOptions(self $type): array
    {
        return match ($type) {
            self::Profiles => [
                'attributesToRetrieve' => ['username', 'slug', 'description', 'picture'],
                'attributesToHighlight' => ['username', 'description'],
                'attributesToCrop' => ['description'],
                'cropMarker' => '...',
            ],
            default => [],
        };
    }
}
