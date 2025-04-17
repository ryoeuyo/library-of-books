<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class UserAlreadyExistsException extends \RuntimeException implements HttpExceptionInterface
{
	public function __construct(string $message = 'User already exists')
	{
		parent::__construct($message);
	}

	public function getStatusCode(): int
	{
		return 409;
	}

	public function getHeaders(): array
	{
		return [];
	}
}