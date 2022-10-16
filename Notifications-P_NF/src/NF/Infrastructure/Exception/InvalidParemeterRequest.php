<?php

namespace App\NF\Infrastructure\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class InvalidParemeterRequest extends Exception
{
    public function __construct()
    {
        parent::__construct('Invalid paremeter request', Response::HTTP_BAD_REQUEST);
    }
}
