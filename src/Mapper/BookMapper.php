<?php

namespace App\Mapper;

use App\Models\DTO\BookListItem;
use App\Models\DTO\OpenBookDTO;
use App\Models\Entity\Book;
use Doctrine\Common\Collections\Collection;

class BookMapper
{
	public function __construct()
	{
	}

	public function toDTO(Book $book) : BookListItem
	{
		return new BookListItem(
			$book->getId(),
			$book->getName(),
		);
	}

	public function toDTOArray(Collection $books): array
	{
		return array_map(
			fn (Book $book) => BookMapper::toDTO($book),
			$books->toArray()
		);
	}

	public function toOpenBookDTO(Book $book) : OpenBookDTO
	{
		return new OpenBookDTO(
			$book->getName(),
			$book->getContent()
		);
	}
}