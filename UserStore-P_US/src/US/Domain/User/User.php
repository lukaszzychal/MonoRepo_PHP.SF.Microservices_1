<?php

namespace App\US\Domain\User;

use App\US\Domain\Address\Address;
use App\US\Domain\AggregateRoot;
use App\US\Domain\Email\Email;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'users', schema: 'user_store')]
class User implements AggregateRoot
{
    // private Collection $eventLog;

    private function __construct(
        #[ORM\Id]
        #[ORM\Column]
        private readonly UserID $uuid,
        #[ORM\Column(length: 100)]
        private readonly string $firstName,
        #[ORM\Column(length: 100)]
        private readonly string $lastName,
        #[ORM\Embedded(Email::class)]
        private readonly Email $email,
        #[ORM\ManyToOne(targetEntity: Address::class, cascade: ['persist'])]
        #[ORM\JoinColumn(name: 'address_id', referencedColumnName: 'uuid')]
        private readonly ?Address $address,
        #[ORM\Column(type: 'datetime', nullable: true)]
        private readonly ?DateTimeImmutable $birthDate,
        #[ORM\Column(length: 100)]
        private readonly ?string $avatar
    ) {
    }

    public static function create(
        UserID $uuid,
        string $firstName,
        string $lastName,
        Email $email,
        ?Address $address = null,
        ?DateTimeImmutable $birthDate = null,
        ?string $avatar = ''
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

        // $this->eventLog->add();
    }

    /**
     * Get the value of uuid.
     */
    public function getUuid(): UserID
    {
        return UserID::fromString($this->uuid);
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
    public function getAddress(): ?Address
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
