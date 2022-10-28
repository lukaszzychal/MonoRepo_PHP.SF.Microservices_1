<?php

declare(strict_types=1);

namespace App\Tests\US\Unit\User;

use App\Tests\US\Provider\Address\AddressProvider;
use App\Tests\US\Provider\Email\EmailProvider;
use App\Tests\US\Provider\User\UserProvider;
use App\US\Domain\Address\Address;
use App\US\Domain\PostalCode\PostalCode;
use App\US\Domain\User\User;
use App\US\Domain\User\UserID;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

/**
 * @group Unit
 *
 * @grop now3
 */
class UserProviderTest extends TestCase
{
    public function testCreate(): void
    {
        /**
         * @var User $user
         */
        $user = UserProvider::create();

        $this->assertInstanceOf(User::class, $user);
        $this->assertIsString($user->getFirstName());
        $this->assertNotEmpty($user->getFirstName());

        $this->assertIsString($user->getLastName());
        $this->assertNotEmpty($user->getLastName());

        $this->assertInstanceOf(UserID::class, $user->getUuid());
        $this->assertTrue(Uuid::isValid($user->getUuid()->uuid->__toString()));

        $this->assertInstanceOf(Address::class, $user->getAddress());
        $this->assertInstanceOf(PostalCode::class, $user->getAddress()->postalCode);

        // @todo  Dokończyć testy
    }

    public function testRandom(): void
    {
        $user1 = UserProvider::random();
        $user2 = UserProvider::random();

        $this->assertNotSame((string) $user1->getUuid(), (string) $user2->getUuid());
        $this->assertNotSame($user1->getEmail(), $user2->getEmail());
    }

    public function testWith(): void
    {
        $user = UserProvider::withData(
            new UserID(Uuid::v4()),
            'Lukasz 2',
            'Z 2',
            EmailProvider::withEmail('my.email@test.pl'),
            AddressProvider::create(),
            new DateTimeImmutable(),
            ''
        );

        $this->assertSame('Lukasz 2', $user->getFirstName());
        $this->assertSame('my.email@test.pl', $user->getEmail());
    }
}
