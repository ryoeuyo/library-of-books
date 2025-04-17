<?php

namespace App\Models\DTO;

readonly class FindedBooksDTO
{
    public function __construct(public ?string $title, public ?string $description, public ?string $url)
    {
    }
}
