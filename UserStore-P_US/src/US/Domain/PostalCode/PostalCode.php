<?php

namespace App\US\Domain\PostalCode;

use App\US\Domain\Address\Country;
use InvalidArgumentException;
use Stringable;
use Webmozart\Assert\Assert;

final class PostalCode implements Stringable
{
    public function __construct(
        public readonly string $value,
        public readonly Country $country
    ) {
        try {
            Assert::notEmpty($value, sprintf('Postal Code can\'t be empty'));
            Assert::notEmpty($country, sprintf('Country can\'t be empty'));
            PostalCodeFormatValidation::valid($this->value, $this->country);
        } catch (InvalidArgumentException $th) {
            throw new InvalidPostalCodeException($value);
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
