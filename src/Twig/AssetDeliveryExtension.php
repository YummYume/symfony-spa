<?php

namespace App\Twig;

use Symfony\UX\LazyImage\BlurHash\BlurHashInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class AssetDeliveryExtension extends AbstractExtension
{
    public const REDIS_CACHE_PREFIX = 'data_uri_';

    public function __construct(
        private readonly string $publicDir,
        private readonly string $fallback,
        private readonly BlurHashInterface $blurHash,
        private readonly \Redis $redis
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('local_asset', [$this, 'getLocalAsset']),
            new TwigFunction('data_uri_thumbnail_cached', [$this, 'createCachedDataUriThumbnail']),
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

    public function createCachedDataUriThumbnail(string $filename, int $width, int $height, int $encodingWidth = 75, int $encodingHeight = 75): string
    {
        $key = sprintf('%s%s', self::REDIS_CACHE_PREFIX, $filename);
        $uri = $this->redis->get($key);

        if (!$uri) {
            $uri = $this->blurHash->createDataUriThumbnail($filename, $width, $height, $encodingWidth, $encodingHeight);

            $this->redis->set($key, $uri, [
                'EX' => 86400,
            ]);
        }

        return $uri;
    }
}
