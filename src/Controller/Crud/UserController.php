<?php

declare(strict_types=1);

namespace App\Controller\Crud;

use App\Mapper\BookMapper;
use App\Mapper\UserMapper;
use App\Models\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('api/user')]
class UserController extends AbstractController
{
    #[Route('/get_all', name: 'api_all_users', methods: ['GET'])]
    public function getAllUsers(UserRepository $userRepository): Response
    {
        return $this->json([
            'users' => $userRepository->findAll(),
        ], context: ['groups' => ['user:read']]);
    }

    #[Route('/me', name: 'api_user_me', methods: ['GET'])]
    public function me(
        #[CurrentUser] User $user,
        UserMapper $userMapper,
        BookMapper $bookMapper,
    ): Response {
        return $this->json($userMapper->toDTO($user, $bookMapper));
    }
}
