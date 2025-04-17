<?php

namespace App\Models\Entity;

use App\Repository\AllowedUserRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AllowedUserRepository::class)]
class AllowedUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'allowedUsers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $allowed = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $allowedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    public function getAllowed(): ?User
    {
        return $this->allowed;
    }

    public function setAllowed(?User $allowed): static
    {
        $this->allowed = $allowed;

        return $this;
    }

    public function getAllowedAt(): ?\DateTimeImmutable
    {
        return $this->allowedAt;
    }

    public function setAllowedAt(\DateTimeImmutable $allowedAt): static
    {
        $this->allowedAt = $allowedAt;

        return $this;
    }
}
