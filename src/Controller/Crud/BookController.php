<?php

declare(strict_types=1);

namespace App\Controller\Crud;

use App\Mapper\BookMapper;
use App\Models\DTO\CreateBookRequest;
use App\Models\DTO\UpdateBookRequest;
use App\Models\Entity\User;
use App\Repository\BookRepository;
use App\Service\CreateBookService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload as RequestAttribute;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/api/book')]
class BookController extends AbstractController
{
    public function __construct(
        private readonly CreateBookService $createBookService,
        private readonly BookMapper $bookMapper,
        private readonly BookRepository $bookRepository,
        private readonly EntityManagerInterface $em)
    {
    }

    #[Route('/my-books', name: 'my_books', methods: ['GET'])]
    public function getAllMyBooks(
        #[CurrentUser] User $user,
    ): Response {
        return $this->json([
            'books' => $this->bookMapper->toDTOArray($user->getBooks()),
        ]);
    }

    #[Route('/get-books/{id}', name: 'get_other_books', methods: ['GET'])]
    public function getOtherUserBooks(
        #[CurrentUser] User $user,
        int $id,
        BookRepository $bookRepository,
    ): Response {
        return $this->json([
            'books' => $bookRepository->findSharedBooks($user->getId(), $id),
        ], 200, [], ['groups' => ['book:read']]);
    }

    #[Route('/open/{id}', name: 'open_book', methods: ['GET'])]
    public function openBook(int $id, #[CurrentUser] User $user): Response
    {
        $book = $this->bookRepository->findOneById($id);

        if (!$book) {
            throw NotFoundHttpException::fromStatusCode(Response::HTTP_NOT_FOUND);
        }

        if ($book->getUser()->getId() !== $user->getId()) {
            throw new AccessDeniedHttpException('You are not allowed to access this book.');
        }

        return $this->json([
            $this->bookMapper->toOpenBookDTO($book),
        ]);
    }

    #[Route('/create', name: 'create_book', methods: ['POST'])]
    public function createBook(
        Request $req,
        #[RequestAttribute] CreateBookRequest $bookRequest,
        #[CurrentUser] User $user,
    ): Response {
        $bookRequest->setFile($req->files->get('file'));

        return $this->json([
            'book_id' => $this->createBookService->createBook($bookRequest, $user),
        ], 200, [], ['groups' => ['book:read']]);
    }

    #[Route('/update', name: 'update_book', methods: ['PUT'])]
    public function updateBook(
        #[RequestAttribute] UpdateBookRequest $request,
        #[CurrentUser] User $user,
    ): Response {
        $book = $this->bookRepository->findOneById($request->id);

        if (!$book) {
            throw NotFoundHttpException::fromStatusCode(Response::HTTP_NOT_FOUND);
        }

        if ($book->getUser()->getId() !== $user->getId()) {
            throw new AccessDeniedHttpException('You are not allowed to access this book.');
        }

        $book->setName($request->title);
        $book->setContent($request->content);
        $book->setUpdatedAt(new \DateTimeImmutable());
        $this->em->flush();

        return $this->json([
            'message' => 'Book updated successfully',
        ]);
    }

    #[Route('/delete/{id}', name: 'delete_book', methods: ['DELETE'])]
    public function deleteBook(int $id, #[CurrentUser] User $user): Response
    {
        $book = $this->bookRepository->findOneById($id);

        if (!$book) {
            throw NotFoundHttpException::fromStatusCode(Response::HTTP_NOT_FOUND);
        }

        if ($book->getUser()->getId() !== $user->getId()) {
            throw new AccessDeniedHttpException('You are not allowed to access this book.');
        }

        $book->setIsDeleted(true);
        $this->em->flush();

        return $this->json([
            'message' => 'Book deleted successfully',
        ]);
    }
}
