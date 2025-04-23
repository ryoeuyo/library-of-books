<?php

namespace App\Repository;

use App\Models\Entity\Book;
use App\Models\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    /**
     * @return Book[] Returns an array of $user books
     */
    public function findAllByUser(User $user): array
    {
        return $this->findBy(['user' => $user, 'isDeleted' => false]);
    }

    public function findOneById(int $id): ?Book
    {
        return $this->find($id);
    }

    /**
     * @return Book[] Returns an array of books
     */
    public function findAllByUserId(int $id): array
    {
        $user = $this->getEntityManager()->getRepository(User::class)->find($id);

        if (!$user) {
            return [];
        }

        return $this->findAllByUser($user);
    }

    public function saveBook(Book $book): void
    {
        $this->getEntityManager()->persist($book);
        $this->getEntityManager()->flush();
    }

    public function deleteBookById(int $id): void
    {
        $book = $this->find($id);

        $this->getEntityManager()->remove($book);
        $this->getEntityManager()->flush();
    }

    /**
     * @return Book[] Returns an array of other user books
     */
    public function findSharedBooks(int $userId, int $otherUserId): array
    {
        return $this->createQueryBuilder('b')
            ->join('b.user', 'owner')
            ->join('owner.allowedUsers', 'au')
            ->where('au.allowed = :userId')
            ->andWhere('owner.id = :otherUserId')
            ->setParameter('userId', $userId)
            ->setParameter('otherUserId', $otherUserId)
            ->getQuery()
            ->getResult();
    }
}
