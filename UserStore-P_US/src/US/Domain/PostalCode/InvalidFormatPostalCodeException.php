<?php

declare(strict_types=1);

namespace App\US\Domain\PostalCode;

use Exception;

final class InvalidFormatPostalCodeException extends Exception
{
    public function __construct(string $postalCode, string $validFormat)
    {
        parent::__construct(sprintf('Invalid Postal Code \'%s\'. Valid format is \'%d\' ', $postalCode, $validFormat));
    }
}
