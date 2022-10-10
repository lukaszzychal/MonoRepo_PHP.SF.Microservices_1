<?php

namespace App\US\Domain\Email;

use Exception;

final class InvalidEmailException extends Exception
{
    public function __construct(string $email)
    {
        parent::__construct(sprintf('The email %s is not a valid email.', $email));
    }
}
