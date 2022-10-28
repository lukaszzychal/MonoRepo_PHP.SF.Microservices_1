<?php

declare(strict_types=1);

namespace App\US\Domain\PostalCode;

use App\US\Domain\Address\Country;
use InvalidArgumentException;
use Stringable;
use Webmozart\Assert\Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final class PostalCode implements Stringable
{
    public function __construct(
        #[ORM\Column(length: 50)]
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
