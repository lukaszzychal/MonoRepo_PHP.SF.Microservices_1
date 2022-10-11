<?php

namespace Unit;

use App\Tests\US\Provider\Address\AddressProvider;
use App\Tests\US\Provider\Email\EmailProvider;
use App\US\Domain\User\User;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\UuidV4;

/**
 * @group Unit
 */
class UserTest extends TestCase
{
    /**
     *
     * @return void
     */
    public function testCreateUser(): void
    {
        $uuid = UuidV4::v4();
        $user = User::create(
            $uuid,
            'Imię',
            'Nazwisko',
            EmailProvider::withEmail('email_testowy@test.com'),
            AddressProvider::defaults(),
            new DateTimeImmutable("+10 year"),
            'url_avatar'

        );

        $this->assertSame($uuid, $user->getUuid());
        $this->assertSame('Imię', $user->getFirstName());
        $this->assertSame('email_testowy@test.com', (string) $user->getEmail());
        $this->assertSame(AddressProvider::DEFAULT_CITY, $user->getAddress()->city);
    }
}
