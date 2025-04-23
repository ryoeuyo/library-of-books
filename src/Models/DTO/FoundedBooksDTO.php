<?php

namespace App\Models\DTO;

readonly class FoundedBooksDTO
{
    public function __construct(public string $title, public string $description, public string $url)
    {
    }
}
