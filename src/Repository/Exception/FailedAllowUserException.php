<?php

namespace App\Repository\Exception;

class FailedAllowUserException extends \RuntimeException
{
    public function __construct(string $message = 'Failed to allow user')
    {
        parent::__construct($message);
    }
}
