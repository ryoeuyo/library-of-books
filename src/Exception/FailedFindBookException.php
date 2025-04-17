<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class FailedFindBookException extends \RuntimeException implements HttpExceptionInterface
{
	public function getStatusCode(): int
	{
		return 500;
	}

	public function getHeaders(): array
	{
		return [];
	}
}