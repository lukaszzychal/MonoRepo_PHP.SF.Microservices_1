<?php

namespace App\US\Infrastructure\Http\CreateUser;

use Exception;
use Symfony\Component\HttpFoundation\Response;

/**
 * @todo dodac logi
 */
final class InvalidParemeterRequest extends Exception
{
    public function __construct()
    {
        parent::__construct(sprintf('Invalid parrameters request'), Response::HTTP_BAD_REQUEST);
    }
}
