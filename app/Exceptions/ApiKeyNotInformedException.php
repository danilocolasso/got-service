<?php

namespace App\Exceptions;

use Exception;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyNotInformedException extends Exception
{
    #[Pure]
    public function __construct()
    {
        parent::__construct(message('missing-api-key', [], 'error'), Response::HTTP_UNAUTHORIZED);
    }
}
