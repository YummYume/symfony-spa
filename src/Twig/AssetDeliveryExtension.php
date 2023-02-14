<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Vich\UploaderBundle\Storage\StorageInterface;

final class AssetDeliveryExtension extends AbstractExtension
{
    public function __construct(
        private readonly StorageInterface $storage,
        private readonly string $publicDir,
        private readonly string $fallback
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('local_asset', [$this, 'getLocalAsset']),
            new TwigFunction('resolve_upload_uri', [$this, 'resolveUploadUri']),
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

    // This is WIP
    public function resolveUploadUri(object|array $obj, ?string $fieldName = null, ?string $className = null): ?string
    {
        return $this->storage->resolveUri($obj, $fieldName, $className);
    }
}
