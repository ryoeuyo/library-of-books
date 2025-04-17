<?php

namespace App\Models\DTO;
use Symfony\Component\Validator\Constraints as Assert;

class RegisterRequest
{
	#[Assert\NotBlank]
	#[Assert\Assert\Length(min: 4, max: 255)]
	private string $login;

	#[Assert\NotBlank]
	#[Assert\Length(min: 8, max: 255)]
	private string $password;

	public function getPassword(): string
	{
		return $this->password;
	}

	public function setPassword(string $password): void
	{
		$this->password = $password;
	}

	public function getLogin(): string
	{
		return $this->login;
	}

	public function setLogin(string $login): void
	{
		$this->login = $login;
	}
}