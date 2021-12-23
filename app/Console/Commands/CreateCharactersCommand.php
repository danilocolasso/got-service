<?php

namespace App\Console\Commands;

use App\Services\CharacterService;
use App\Services\GotService;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;

class CreateCharactersCommand extends Command
{
    protected $signature = 'characters:create';
    protected $description = 'Send Character information fetched from two api\'s to a remote service.';

    private array $quotes;
    private array $characters;
    private array $errors = [];

    public function handle(): void
    {
        $this->info(trans('messages.fetching-characters'));
        $this->characters = GotService::getCharacters();

        $this->info(trans('messages.fetching-quotes'));
        $this->quotes = GotService::getQuotes();

        $this->info(trans('messages.deleting-all-characters'));
        CharacterService::deleteAll();

        $progressBar = $this->output->createProgressBar(count($this->characters));
        $progressBar->start();

        foreach ($this->characters as $character) {
            $created = $this->createCharacter($character);
            $character['id'] = $this->getCreatedCharacterId($created);
            $character['id']
                ? $this->createCharacterQuotes($character)
                : $this->skipCharacterQuotes($character);

            $progressBar->advance();
        }

        $progressBar->finish();

        $this->info(trans('messages.all-done', [
            'created' => count($this->characters) - count($this->errors),
            'total' => count($this->characters),
        ]));
    }

    private function createCharacter(array $character): array
    {
        $this->customInfo(trans('messages.creating-character', ['name' => $character['fullName']]));

        return CharacterService::createCharacter([
            'name' => $character['fullName'],
            'image_url' => $character['imageUrl'],
        ]);
    }

    private function createCharacterQuotes(array $character): void
    {
        $this->customInfo(trans('messages.adding-quotes'));

        foreach ($this->getCharacterQuotes($character) as $quote) {
            CharacterService::createQuote([
                'character_id' => $character['id'],
                'text' => $quote,
            ]);
        }
    }

    private function skipCharacterQuotes(array $character): void
    {
        $this->errors[] = $character;
        $this->customInfo(trans('messages.character-quotes-skipped', ['name' => $character['fullName']]));
    }

    private function getCharacterQuotes(array $character): array
    {
        return $this->quotes[strtolower($character['firstName'])] ?? [];
    }

    private function getCreatedCharacterId(array $createdCharacter): ?int
    {
        return isset($createdCharacter['data'])
            ? Arr::first($createdCharacter['data']['insert_Character']['returning'])['id']
            : null;
    }

    private function customInfo(string $message): void
    {
        $this->output->write("<info> {$message}</info>");
    }
}
