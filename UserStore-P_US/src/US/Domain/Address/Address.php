<?php

namespace App\US\Domain\Address;

use App\US\Domain\PostalCode\PostalCode;
use InvalidArgumentException;
use Webmozart\Assert\Assert;

class Address
{
    public function __construct(
        public readonly string $street,
        public readonly string $houseNumber,
        public readonly ?string $apartmentNumber,
        public readonly string $city,
        public readonly PostalCode $postalCode,
        public readonly Country $country
    ) {
        try {
            Assert::notEmpty($street);
            Assert::notEmpty($houseNumber);
            Assert::notEmpty($street);
            Assert::notEmpty($city);
            Assert::notEmpty($postalCode);
            Assert::notEmpty((string) $postalCode);
            Assert::notEmpty($country);
            Assert::notEmpty($country->value);
        } catch (InvalidArgumentException $e) {
            throw new InvalidAddreessException($this);
        }
    }
}
