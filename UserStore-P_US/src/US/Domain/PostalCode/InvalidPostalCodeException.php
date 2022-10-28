<?php

declare(strict_types=1);

namespace App\US\Domain\PostalCode;

use Exception;

final class InvalidPostalCodeException extends Exception
{
    public function __construct(string $postalCode)
    {
        parent::__construct(sprintf('Invalid Postal Code %s', $postalCode));
    }
}
