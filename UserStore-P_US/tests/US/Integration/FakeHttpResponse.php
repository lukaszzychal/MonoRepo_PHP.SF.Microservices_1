<?php

declare(strict_types=1);

namespace App\Tests\US\Integration;

use Symfony\Component\HttpClient\Response\MockResponse;

final class FakeHttpResponse
{
    public function __construct(
        public readonly string $url,
        public readonly string $method,
        public readonly MockResponse $resonse
    ) {
    }
}
