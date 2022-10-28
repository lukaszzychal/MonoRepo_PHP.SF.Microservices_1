<?php

declare(strict_types=1);

namespace Unit;

use App\Tests\US\Provider\Address\AddressProvider;
use App\Tests\US\Provider\Email\EmailProvider;
use App\US\Domain\User\User;
use App\US\Domain\User\UserID;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\UuidV4;

/**
 * @group Unit
 */
class UserTest extends TestCase
{
    public function testCreateUser(): void
    {
        $uuid = UuidV4::v4();
        $user = User::create(
            new UserID($uuid),
            'Imię',
            'Nazwisko',
            EmailProvider::withEmail('email_testowy@test.com'),
            AddressProvider::defaults(),
            new DateTimeImmutable('+10 year'),
            'url_avatar'
        );
        $this->assertSame((string) $uuid, (string) $user->getUuid()->uuid);
        $this->assertSame('Imię', $user->getFirstName());
        $this->assertSame('email_testowy@test.com', (string) $user->getEmail());
        $this->assertSame(AddressProvider::DEFAULT_CITY, $user->getAddress()->city);
    }
}
