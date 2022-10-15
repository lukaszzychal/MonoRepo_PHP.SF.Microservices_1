<?php

namespace App\US\Shared;

use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;

interface ExceptionInterface
{
    public function getTitle(): string;
    public function getDetails(): string;
    public function getSource(): string;
    public function getStatusCode(): int;
    public function toArray(): array;
    public function toJsonResponse(): JsonResponse;
}
