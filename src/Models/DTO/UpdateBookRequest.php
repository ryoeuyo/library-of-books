<?php

namespace App\Models\DTO;

class UpdateBookRequest
{
    public function __construct(public int $id, public string $title, public string $content)
    {
    }
}
