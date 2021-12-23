<?php

namespace App\Http\Controllers;

use App\Services\CharacterService;
use Illuminate\Http\JsonResponse;

class CharactersController extends Controller
{
    public function index(): JsonResponse
    {
        $result = CharacterService::characters();

        return response()->json($result);
    }
}
