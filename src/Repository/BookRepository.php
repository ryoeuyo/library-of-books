<?php

namespace App\Repository;

use App\Entity\AllowedUser;
use App\Entity\Book;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 */
class BookRepository extends ServiceEntityRepository
{
	private BookRepository $bookRepository;

	public function __construct(ManagerRegistry $registry, BookRepository $bookRepository)
    {
        parent::__construct($registry, Book::class);
		$this->bookRepository = $bookRepository;
	}

    /**
     * @return Book[] Returns an array of $user books
     */
    public function findAllByUser(User $user): array
    {
		return $this->findBy(['user' => $user, 'isDeleted' => false]);
    }

	public function findAllByUserId(int $id): array
	{
		$user = $this->getEntityManager()->getRepository(User::class)->find($id);

		if (!$user) {
			return [];
		}

		return $this->findAllByUser($user);
	}

	public function findOneById(int $id): ?Book
	{
		return $this->find($id);
	}

	public function saveBook(Book $book): void
	{
		$this->getEntityManager()->persist($book);
	}

	public function deleteBookById(int $id): void
	{
		$book = $this->bookRepository->find($id);

		$this->getEntityManager()->remove($book);
		$this->getEntityManager()->flush();
	}

	public function findSharedBooks(int $userId, int $otherUserId): array
	{
		return $this->createQueryBuilder('b')
			->join('b.user', 'owner')
			->join(AllowedUser::class, 'au', 'WITH', 'au.owner = owner')
			->where('au.allowedUser = :userId')
			->andWhere('owner.id = :otherUserId')
			->setParameters([
				'userId' => $userId,
				'otherUserId' => $otherUserId
			])
			->getQuery()
			->getResult();
	}
}
