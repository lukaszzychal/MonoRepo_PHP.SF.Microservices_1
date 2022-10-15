<?php

namespace App\US\Infrastructure\Http\CreateUser;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use US\Infrastructure\Http\ResponseInterface;

/**
 * @todo dodac logi
 */
class CreateUserResponse implements ResponseInterface
{
    private function __construct()
    {
    }

    public static function create(): JsonResponse
    {
        return new JsonResponse('User created', Response::HTTP_CREATED);
    }
}
