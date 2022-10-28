<?php

declare(strict_types=1);

namespace App\NF\Infrastructure\Response;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class NotificationResponse extends JsonResponse
{
    public function __construct()
    {
        parent::__construct([
            'code' => Response::HTTP_OK,
            'message' => 'Wysłano powiadomminie',
        ], Response::HTTP_OK);
    }
}
