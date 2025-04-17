<?php

namespace App\Mapper;

use App\Models\DTO\UserDTO;
use App\Models\Entity\User;

class UserMapper
{
	public function __construct()
	{
	}

	public function toDTO(User $user, BookMapper $bookMapper): UserDTO {
		return new UserDTO(
			$user->getId(),
			$user->getLogin(),
			$bookMapper->toDTOArray($user->getBooks()),
		);
	}

	public function toListDTO(array $users): array {
		return array_map(
			fn (User $user) => $this->toDTO($user),
			$users
		);
	}
}