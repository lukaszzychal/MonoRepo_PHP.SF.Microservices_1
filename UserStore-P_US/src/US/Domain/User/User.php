<?php

namespace App\US\Domain\User;

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
        private readonly Address $address,
        private readonly ?DateTimeImmutable $birthDate,
        private readonly ?string $avatar

    ) {
    }

    public static function create(
        Uuid $uuid,
        string $firstName,
        string $lastName,
        Email $email,
        Address $address,
        ?DateTimeImmutable $birthDate,
        ?string $avatar
    ): User {
        return new self(
            $uuid,
            $firstName,
            $lastName,
            $email,
            $address,
            $birthDate,
            $avatar

        );
    }

    /**
     * Get the value of uuid.
     */
    public function getUuid(): Uuid
    {
        return $this->uuid;
    }

    /**
     * Get the value of firstName.
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * Get the value of lastName.
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * Get the value of email.
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Get the value of address.
     */
    public function getAddress(): Address
    {
        return $this->address;
    }

    /**
     * Get the value of birthDate.
     */
    public function getBirthDate(): ?DateTimeImmutable
    {
        return $this->birthDate;
    }

    /**
     * Get the value of avatar.
     */
    public function getAvatar(): ?string
    {
        return $this->avatar;
    }
}
