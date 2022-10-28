<?php

declare(strict_types=1);

namespace App\US\Shared;

use Symfony\Component\HttpFoundation\JsonResponse;
use Exception as GlobalException;

class Exception extends GlobalException implements ExceptionInterface
{
    public function __construct(
        protected readonly int $statusCode,
        protected readonly string $title,
        protected readonly string $details,
        protected readonly string $source
    ) {
        parent::__construct($this->toString(), $statusCode);
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDetails(): string
    {
        return $this->details;
    }

    public function getSource(): string
    {
        return $this->source;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @return array<string, int|string>
     */
    public function toArray(): array
    {
        return [
            'code' => $this->statusCode,
            'title' => $this->title,
            'details' => $this->details,
            'source' => $this->source,
        ];
    }

    public function toJsonResponse(): JsonResponse
    {
        return new JsonResponse($this->toArray(), $this->statusCode);
    }

    public function toString(): string
    {
        return implode(';', $this->toArray());
    }
}
