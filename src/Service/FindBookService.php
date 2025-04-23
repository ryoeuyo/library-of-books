<?php

namespace App\Service;

use App\Exception\FailedFindBookException;
use App\Models\DTO\FoundedBooksDTO;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class FindBookService
{
    public function __construct()
    {
    }

    public function FindBook(string $query): array
    {
        $client = new Client();
        try {
            $response = $client->get('https://www.googleapis.com/books/v1/volumes?q='.$query);
        } catch (GuzzleException $e) {
            throw new FailedFindBookException('Failed to fetch Google books: '.$e->getMessage());
        }

        $data = json_decode((string) $response->getBody(), true);

        if (!isset($data['items'])) {
            return [];
        }

        $foundBooks = [];

        foreach ($data['items'] as $item) {
            if (!isset($item['volumeInfo']['title'])) {
                continue;
            }

            if (!isset($item['selfLink'])) {
                continue;
            }

            if (!isset($item['volumeInfo']['description'])) {
                continue;
            }

            $foundBooks[] = new FoundedBooksDTO(
                $item['volumeInfo']['title'],
                $item['volumeInfo']['description'],
                $item['selfLink'],
            );
        }

        return $foundBooks;
    }
}
