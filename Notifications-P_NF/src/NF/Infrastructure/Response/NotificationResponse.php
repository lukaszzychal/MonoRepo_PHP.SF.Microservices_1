<?php

namespace App\NF\Infrastructure\Response;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class NotificationResponse extends JsonResponse
{
    public function __construct()
    {
        parent::__construct('Wysłano powiadomminie', Response::HTTP_CREATED);
    }
}
