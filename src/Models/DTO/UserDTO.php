<?php

namespace App\Models\DTO;

class UserDTO
{
	public function __construct(public int $id, public string $login, public array $books)
	{
	}
}