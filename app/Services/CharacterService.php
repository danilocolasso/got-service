<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class CharacterService
{
    public static function createCharacter(array $data): array
    {
        Validator::validate($data, [
            'name' => 'required|string',
            'image_url' => 'required|string',
        ]);

        $query = <<<GQL
        mutation CreateCharacter {
          insert_Character(objects: %s) {
            returning {
              id
            }
          }
        }
        GQL;

        return self::send($query, $data, 'CreateCharacter');
    }

    public static function createQuote(array $data): array
    {
        Validator::validate($data, [
            'text' => 'required|string',
            'character_id' => 'required|int'
        ]);

        $query = <<<GQL
        mutation CreateQuote {
          insert_Quote(objects: %s) {
            returning {
              id
              text
            }
          }
        }
        GQL;

        return self::send($query, $data, 'CreateQuote');
    }

    public static function characters(): array
    {
        $query = <<<GQL
        {
          Character {
            Quotes {
              text
              id
            }
            id
            image_url
            name
          }
        }
        GQL;

        return self::send($query);
    }

    public static function deleteAll(): array
    {
        $data = [
            'id' => [
                '_gt' => 0,
            ]
        ];

        return self::delete($data);
    }

    private static function delete(array $data): array
    {
        $query = <<<GQL
        mutation DeleteAll {
          delete_Character(where: %s) {
            affected_rows
          }
        }
        GQL;

        return self::send($query, $data);
    }

    private static function send(string $query, array $data = [], ?string $operation = null): array
    {
        $headers = [
            'x-hasura-admin-secret' => env('X_HASURA_ADMIN_SECRET'),
        ];

        $body = [
            'query' => $data
                ? sprintf($query, graphql_encode($data))
                : $query,
        ];

        if ($operation) {
            $body['operationName'] = $operation;
        }

        $response = Http::withHeaders($headers)
            ->withBody(json_encode($body), 'application/json')
            ->post(env('CHALLENGE_ENDPOINT'));

        return $response->json();
    }
}
