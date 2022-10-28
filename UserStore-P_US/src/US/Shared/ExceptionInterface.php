<?php

declare(strict_types=1);

namespace App\US\Shared;

use Symfony\Component\HttpFoundation\JsonResponse;

interface ExceptionInterface
{
    public function getTitle(): string;

    public function getDetails(): string;

    public function getSource(): string;

    public function getStatusCode(): int;

    /**
     * @return string[]
     */
    public function toArray(): array;

    public function toJsonResponse(): JsonResponse;
}
