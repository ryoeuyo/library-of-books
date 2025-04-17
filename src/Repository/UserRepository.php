<?php

namespace App\Repository;

use App\Models\Entity\AllowedUser;
use App\Models\Entity\User;
use App\Repository\Exception\FailedAllowUserException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function saveUser(User $user): void
    {
        $this->getEntityManager()->persist($user);
    }

    public function findUserByLogin(string $login): ?User
    {
        return $this->findOneBy(['login' => $login]);
    }

    public function findUserById(int $id): ?User
    {
        return $this->find($id);
    }

    /**
     * @throws FailedAllowUserException
     */
    public function allowUserByIds(int $id, int $otherId): void
    {
        $em = $this->getEntityManager();

        $owner = $em->getRepository(User::class)->find($id);
        $other = $em->getRepository(User::class)->find($otherId);

        if (!$owner || !$other) {
            throw new FailedAllowUserException();
        }

        $allowedUser = new AllowedUser();
        $allowedUser->setOwner($owner);
        $allowedUser->setAllowed($other);
        $allowedUser->setAllowedAt(new \DateTimeImmutable());

        $em->persist($allowedUser);
        $em->flush();
    }
}
