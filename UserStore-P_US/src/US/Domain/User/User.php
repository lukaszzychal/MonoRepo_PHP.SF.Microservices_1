<?php

namespace US\Domain\User;

use App\US\Domain\Address\Address;
use App\US\Domain\Email\Email;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;

class User
{

    private function __construct(
        private readonly Uuid $uuid,
        private readonly string $firstName,
        private readonly string $lastName,
        private readonly Email $email,
        private readonly DateTimeImmutable $birthDate,
        private readonly string $avatar,
        private readonly Address $address

    ) {
    }

    public static function create(
        Uuid $uuid,
        string $firstName,
        string $lastName,
        Email $email,
        ?DateTimeImmutable $birthDate,
        ?string $avatar,
        Address $address
    ): User {
        return new self(
            $uuid,
            $firstName,
            $lastName,
            $email,
            $birthDate,
            $avatar,
            $address
        );
    }

    /**
     * Get the value of uuid
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * Get the value of firstName
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Get the value of lastName
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Get the value of email
     */
    public function getEmail()
    {
        return $this->email;
    }



    /**
     * Get the value of address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Get the value of birthDate
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * Get the value of avatar
     */
    public function getAvatar()
    {
        return $this->avatar;
    }
}
