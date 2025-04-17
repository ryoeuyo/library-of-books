<?php

declare(strict_types=1);

namespace App\Controller;

use App\Models\DTO\RegisterRequest;
use App\Service\RegisterUserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload as RequestAttribute;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/auth')]
class RegistrationUserController extends AbstractController
{
    public function __construct(private readonly RegisterUserService $registerUserService)
    {
    }

    #[Route('/register', name: 'api_register', methods: ['POST'])]
    public function register(
        #[RequestAttribute] RegisterRequest $request,
    ): Response {
        return $this->json([
            'user_id' => $this->registerUserService->registerUser($request),
        ]);
    }
}
