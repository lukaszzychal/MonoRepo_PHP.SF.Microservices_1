<?php

declare(strict_types=1);

namespace App\US\Infrastructure\Http\CreateUser;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use US\Infrastructure\Http\ResponseInterface;

/**
 * @todo dodac logi
 */
final class CreateUserResponse implements ResponseInterface
{
    private function __construct()
    {
    }

    public static function create(string $id): JsonResponse
    {
        return new JsonResponse([
            'code' => Response::HTTP_CREATED,
            'message' => 'User created: #' . $id,
        ], Response::HTTP_CREATED);
    }
}
