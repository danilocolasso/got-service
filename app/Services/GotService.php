<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GotService
{
    // There are many name variations between the 2 APIs
    private const NAME_VARIATIONS = [
        'jaime' => 'jamie',
    ];

    public static function getCharacters(): array
    {
        $endpoint = env('THRONES_API_ENDPOINT');
        $response = Http::get($endpoint);

        return $response->json();
    }

    public static function getQuotes(): array
    {
        $endpoint = env('GOT_QUOTES_ENDPOINT');
        $response = Http::get($endpoint);

        $characters = $response->json();
        $quotes = [];

        foreach ($characters as $character) {
            $index = self::NAME_VARIATIONS[$character['slug']] ?? $character['slug'];
            $quotes[$index] = $character['quotes'];
        }

        return $quotes;
    }
}
