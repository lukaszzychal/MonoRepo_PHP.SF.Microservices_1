<?php

declare(strict_types=1);

namespace App\NF\Infrastructure\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;

final class InvalidParemeterRequest extends Exception
{
    public function __construct(array $parrameterNames = []) // @phpstan-ignore-line
    {
        parent::__construct('Invalid paremeter [ ' . implode(',', $parrameterNames) . ' ] request', Response::HTTP_BAD_REQUEST);
    }
}
