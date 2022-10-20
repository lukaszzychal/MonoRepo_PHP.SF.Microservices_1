<?php

namespace App\NF\Infrastructure\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class InvalidParemeterRequest extends Exception
{
    public function __construct(array $parrameterNames)
    {
        parent::__construct('Invalid paremeter [ ' . implode(',', $parrameterNames) . ' ] request', Response::HTTP_BAD_REQUEST);
    }
}
