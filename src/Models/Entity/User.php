<?php

namespace App\Models\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('user:read')]
    private ?int $id = null;

    #[ORM\Column(length: 32, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 8, max: 32)]
    #[Groups('user:read')]
    private ?string $login = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 8, max: 255)]
    private ?string $hash_password = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Groups('user:read')]
    private ?\DateTimeImmutable $registeredAt = null;

    /**
     * @var Collection<int, AllowedUser>
     */
    #[ORM\OneToMany(targetEntity: AllowedUser::class, mappedBy: 'owner')]
    #[Groups('user:read')]
    private Collection $allowedUsers;

    /**
     * @var Collection<int, Book>
     */
    #[ORM\OneToMany(targetEntity: Book::class, mappedBy: 'user')]
    #[Groups('user:read')]
    private Collection $books;

    public function __construct()
    {
        $this->allowedUsers = new ArrayCollection();
        $this->books = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): static
    {
        $this->login = $login;

        return $this;
    }

    public function getHashPassword(): ?string
    {
        return $this->hash_password;
    }

    public function setPassword(string $hash_password, UserPasswordHasherInterface $passwordHasher): static
    {
        $this->hash_password = $passwordHasher->hashPassword($this, $hash_password);

        return $this;
    }

    public function getRegisteredAt(): ?\DateTimeImmutable
    {
        return $this->registeredAt;
    }

    public function setRegisteredAt(\DateTimeImmutable $registeredAt): static
    {
        $this->registeredAt = $registeredAt;

        return $this;
    }

    /**
     * @return Collection<int, AllowedUser>
     */
    public function getAllowedUsers(): Collection
    {
        return $this->allowedUsers;
    }

    public function addAllowedUser(AllowedUser $allowedUser): static
    {
        if (!$this->allowedUsers->contains($allowedUser)) {
            $this->allowedUsers->add($allowedUser);
            $allowedUser->setOwner($this);
        }

        return $this;
    }

    public function removeAllowedUser(AllowedUser $allowedUser): static
    {
        if ($this->allowedUsers->removeElement($allowedUser)) {
            // set the owning side to null (unless already changed)
            if ($allowedUser->getOwner() === $this) {
                $allowedUser->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Book>
     */
    public function getBooks(): Collection
    {
        return $this->books;
    }

    public function addBook(Book $book): static
    {
        if (!$this->books->contains($book)) {
            $this->books->add($book);
            $book->setUser($this);
        }

        return $this;
    }

    public function removeBook(Book $book): static
    {
        if ($this->books->removeElement($book)) {
            // set the owning side to null (unless already changed)
            if ($book->getUser() === $this) {
                $book->setUser(null);
            }
        }

        return $this;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->login;
    }

    public function getPassword(): ?string
    {
        return $this->hash_password;
    }
}
