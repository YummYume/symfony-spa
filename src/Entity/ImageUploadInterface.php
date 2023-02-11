<?php

namespace App\Entity;

use Symfony\Component\HttpFoundation\File\File;

interface ImageUploadInterface
{
    public function getFile(): ?File;

    public function setFile(?File $file): static;

    public function getFileName(): ?string;

    public function setFileName(?string $fileName): static;

    public function getSize(): ?int;

    public function setSize(?int $size): static;

    public function getMimeType(): ?string;

    public function setMimeType(?string $mimeType): static;

    public function getOriginalName(): ?string;

    public function setOriginalName(?string $originalName): static;

    public function getDimensions(): ?array;

    public function setDimensions(?array $dimensions): static;
}
