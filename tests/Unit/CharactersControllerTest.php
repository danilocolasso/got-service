<?php

namespace Tests\Unit;

use App\Http\Controllers\CharactersController;
use PHPUnit\Framework\TestCase;

class CharactersControllerTest extends TestCase
{
    public function testGetAllCharactersShouldReturnAListOfThem()
    {
        $controller = new CharactersController();
        $result = $controller->all();
    }
}
