<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\FindBookService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/book')]
class FindBookController extends AbstractController
{
	#[Route('/find/{query}', name: 'find', methods: ['GET'])]
	public function find(string $query, FindBookService $findBookService): Response
	{
		return $this->json(
			$findBookService->findBook($query),
		);
	}
}
