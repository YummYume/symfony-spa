<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class AssetDeliveryExtension extends AbstractExtension
{
    public function __construct(
        private readonly string $publicDir,
        private readonly string $fallback
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('local_asset', [$this, 'getLocalAsset']),
        ];
    }

    public function getLocalAsset(string $path, bool $fallbackIfNotFound = true): string
    {
        $assetPath = sprintf('%s%s', $this->publicDir, $path);

        if ($fallbackIfNotFound && !file_exists($assetPath)) {
            return sprintf('%s%s', $this->publicDir, $this->fallback);
        }

        return $assetPath;
    }
}
