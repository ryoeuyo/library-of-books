<?php

declare(strict_types=1);

namespace App\Controller;

use App\Models\Entity\User;
use App\Repository\Exception\FailedAllowUserException;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/api/user')]
class UserAccessLibraryController extends AbstractController
{
    #[Route('/access/{id}')]
    public function AccessUser(
        int $id,
        #[CurrentUser] User $user,
        UserRepository $userRepository,
    ): Response {
        try {
            $userRepository->allowUserByIds($user->getId(), $id);
        } catch (FailedAllowUserException $exception) {
            return $this->json([
                'error' => $exception->getMessage(),
            ], 500);
        }

        return $this->json([
            'message' => 'User has been granted',
        ]);
    }
}
