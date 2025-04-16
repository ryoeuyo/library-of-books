<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 32)]
    private ?string $login = null;

    #[ORM\Column(length: 255)]
    private ?string $hash_password = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $registeredAt = null;

    /**
     * @var Collection<int, AllowedUser>
     */
    #[ORM\OneToMany(targetEntity: AllowedUser::class, mappedBy: 'owner')]
    private Collection $allowedUsers;

    /**
     * @var Collection<int, Book>
     */
    #[ORM\OneToMany(targetEntity: Book::class, mappedBy: 'userId')]
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

    public function setHashPassword(string $hash_password): static
    {
        $this->hash_password = $hash_password;

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
}
