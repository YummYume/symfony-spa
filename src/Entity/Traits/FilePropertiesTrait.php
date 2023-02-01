<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait FilePropertiesTrait
{
    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $fileName = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $size = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $mimeType = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $originalName = null;

    #[ORM\Column(type: 'json', nullable: true)]
    private array $dimensions = [];

    public function getFileName(): ?string
    {
        return $this->imageName;
    }

    public function setFileName(?string $fileName): static
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(?int $size): static
    {
        $this->size = $size;

        return $this;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function setMimeType(?string $mimeType): static
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    public function getOriginalName(): ?string
    {
        return $this->originalName;
    }

    public function setOriginalName(?string $originalName): static
    {
        $this->originalName = $originalName;

        return $this;
    }

    public function getDimensions(): array
    {
        return $this->dimensions;
    }

    public function setDimensions(array $dimensions): static
    {
        $this->dimensions = $dimensions;

        return $this;
    }
}
