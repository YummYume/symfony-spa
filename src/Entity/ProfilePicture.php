<?php

namespace App\Entity;

use App\Entity\Traits\BlameableTrait;
use App\Entity\Traits\FilePropertiesTrait;
use App\Entity\Traits\TimestampableTrait;
use App\Repository\UploadFileRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: UploadFileRepository::class)]
class ProfilePicture
{
    use BlameableTrait;
    use FilePropertiesTrait;
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Vich\UploadableField(
        mapping: 'profile_picture',
        fileNameProperty: 'fileName',
        size: 'size',
        mimeType: 'mimeType',
        originalName: 'originalName',
        dimensions: 'dimensions'
    )]
    private ?File $file = null;

    #[ORM\OneToOne(mappedBy: 'picture')]
    private ?Profile $profile = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setFile(?File $file = null): static
    {
        $this->file = $file;

        if (null !== $file) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime();
        }

        return $this;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function getProfile(): ?Profile
    {
        return $this->profile;
    }
}
