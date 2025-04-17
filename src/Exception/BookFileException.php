<?php

namespace App\Exception;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class BookFileException extends FileException implements HttpExceptionInterface
{
    public function __construct(string $message = 'Failed to read uploaded file')
    {
        parent::__construct($message);
    }

    public function getStatusCode(): int
    {
        return 500;
    }

    public function getHeaders(): array
    {
        return [];
    }
}
