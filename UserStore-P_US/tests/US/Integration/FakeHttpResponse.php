<?php

namespace App\Tests\US\Integration;

use Symfony\Component\HttpClient\Response\MockResponse;

class FakeHttpResponse
{
    public function __construct(
        public readonly string $url,
        public readonly string $method,
        public readonly MockResponse $resonse
    ) {
    }
}
