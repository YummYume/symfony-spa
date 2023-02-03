<?php

namespace App\Entity;

use App\Entity\Traits\BlameableTrait;
use App\Entity\Traits\TimestampableTrait;
use App\Enum\UserRoleEnum;
use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'user.email.unique', errorPath: 'email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface, \Serializable
{
    use BlameableTrait;
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?Uuid $id = null;

    #[ORM\Column(type: Types::STRING, length: 180, unique: true)]
    #[Assert\NotBlank(message: 'user.email.not_blank')]
    #[Assert\Length(max: 180, maxMessage: 'user.email.max_length')]
    #[Assert\Email(message: 'user.email.invalid')]
    private ?string $email = null;

    #[ORM\Column(type: Types::JSON)]
    private array $roles = [];

    #[ORM\Column(type: Types::STRING)]
    private ?string $password = null;

    #[Assert\Regex(
        pattern: '/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/',
        message: 'user.password.valid'
    )]
    #[Assert\NotCompromisedPassword(message: 'user.password.not_compromised')]
    #[Assert\When(
        expression: 'this.getPassword() in [null, ""]',
        constraints: [
            new Assert\NotBlank(message: 'user.password.not_blank'),
        ],
    )]
    private ?string $plainPassword = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $isVerified = false;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    #[Assert\Valid]
    private ?Profile $profile = null;

    public function serialize()
    {
        return serialize([
            'id' => $this->id,
            'email' => $this->email,
            'password' => $this->password,
            'isVerified' => $this->isVerified,
        ]);
    }

    public function unserialize(string $data)
    {
        $fields = unserialize($data);

        $this->id = $fields['id'] ?? null;
        $this->email = $fields['email'] ?? null;
        $this->password = $fields['password'] ?? null;
        $this->isVerified = $fields['isVerified'] ?? false;
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = UserRoleEnum::User->value;

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getProfile(): ?Profile
    {
        return $this->profile;
    }

    public function setProfile(Profile $profile): self
    {
        if ($profile->getUser() !== $this) {
            $profile->setUser($this);
        }

        $this->profile = $profile;

        return $this;
    }
}
