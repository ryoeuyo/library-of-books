<?php

namespace App\Service;

use App\Exception\UserAlreadyExistsException;
use App\Models\DTO\RegisterRequest;
use App\Models\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly final class RegisterUserService
{
	public function __construct(
		private UserRepository $userRepository,
		private UserPasswordHasherInterface $userPasswordHasher,
		private EntityManagerInterface $em)
	{
	}

	/**
	 * @throws UserAlreadyExistsException
	 */
	public function registerUser(RegisterRequest $request) : int
	{
		if ($this->userRepository->findUserByLogin($request->getLogin())) {
			throw new UserAlreadyExistsException();
		}

		$user = (new User())
			->setLogin($request->getLogin())
			->setPassword($request->getPassword(), $this->userPasswordHasher)
			->setRegisteredAt(new \DateTimeImmutable());

		$this->em->persist($user);
		$this->em->flush();

		return $user->getId();
	}
}