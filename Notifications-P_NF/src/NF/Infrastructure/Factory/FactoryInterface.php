<?php

declare(strict_types=1);

namespace App\NF\Infrastructure\Factory;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

interface FactoryInterface
{
    public function request(Request $request): RequestInterface;

    public function command(Request $request): CommandInterface;

    public function response(): JsonResponse;
}
