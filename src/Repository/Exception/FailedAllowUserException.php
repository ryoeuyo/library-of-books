<?php

	namespace App\Repository\Exception;

	class FailedAllowUserException extends \Exception
	{
		public function __construct(string $message = "Failed to allow user")
		{
			parent::__construct($message);
		}
	}