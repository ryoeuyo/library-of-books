<?php

namespace App\Service;

use App\Exception\BookFileException;
use App\Models\DTO\CreateBookRequest;
use App\Models\Entity\Book;
use App\Models\Entity\User;
use App\Repository\BookRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final readonly class CreateBookService
{
    public function __construct(private BookRepository $bookRepository)
    {
    }

    public function createBook(CreateBookRequest $request, User $user): int
    {
        $content = $request->getContent();
        $file = $request->getFile();
        if (empty($content)) {
            if (!$file instanceof UploadedFile) {
                throw new BookFileException('Neither content nor file provided');
            }

            if (0 === $file->getSize()) {
                throw new BookFileException('Uploaded file is empty');
            }

            try {
                $content = file_get_contents($file->getPathname());
                if (empty($content)) {
                    throw new BookFileException('File content is empty');
                }
            } catch (\Exception $e) {
                throw new BookFileException('File read error: '.$e->getMessage());
            }
        }

        $book = (new Book())
        ->setName($request->getTitle())
        ->setContent($content)
        ->setUser($user)
        ->setCreatedAt(new \DateTimeImmutable())
        ->setUpdatedAt(new \DateTimeImmutable());

        $this->bookRepository->saveBook($book);

        return $book->getId();
    }
}
