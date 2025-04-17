<?php

namespace App\Models\DTO;

class OpenBookDTO
{
	public function __construct(public string $title, public string $content)
	{}
}