<?php

declare(strict_types=1);

namespace App\US\Domain\Address;

use App\US\Domain\PostalCode\PostalCode;
use Exception;

final class InvalidAddreessException extends Exception
{
    public function __construct(Address $address)
    {
        $address = (array) $address;
        $addresData = '';
        foreach ($address as $key => $value) {
            if ($value instanceof Country) {
                $addresData .= " {$key}: {$value->value} \n";
                continue;
            }
            if ($value instanceof PostalCode) {
                $addresData .= " {$key}: {$value->value} \n";
                continue;
            }
            $addresData .= " {$key}: {$value} \n";
        }
        parent::__construct(
            sprintf(
                'The Address data %s is not a valid Data. Please Cheeck again',
                $addresData
            )
        );
    }
}
