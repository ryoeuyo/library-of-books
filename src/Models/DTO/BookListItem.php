<?php

namespace App\Models\DTO;

class BookListItem
{
	public function __construct(
		public int $id,
		public string $name,
	) {}
}