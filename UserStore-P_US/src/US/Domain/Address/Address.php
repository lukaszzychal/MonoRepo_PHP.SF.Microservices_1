<?php

namespace App\US\Domain\Address;

use App\US\Domain\PostalCode\PostalCode;
use InvalidArgumentException;
use Webmozart\Assert\Assert;
use Doctrine\ORM\Mapping as ORM;;

use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: AddressRepository::class)]
#[ORM\Table(name: 'address', schema: 'user_store')]
final class Address
{
    public function __construct(
        #[ORM\Id]
        #[ORM\Column]
        private readonly Uuid $uuid,

        #[ORM\Column(length: 100)]
        public readonly string $street,

        #[ORM\Column(length: 100)]
        public readonly string $houseNumber,

        #[ORM\Column(length: 100)]
        public readonly ?string $apartmentNumber = null,

        #[ORM\Column(length: 100)]
        public readonly string $city,

        #[ORM\Embedded(PostalCode::class)]
        public readonly PostalCode $postalCode,

        #[ORM\Column(type: 'string', length: 255, enumType: Country::class)]
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
