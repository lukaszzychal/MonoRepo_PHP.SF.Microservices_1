<?php

declare(strict_types=1);

namespace Appp\Tests\US\Unit\Email;

use App\Tests\US\Provider\Email\EmailProvider;
use App\US\Domain\Email\Email;
use PHPUnit\Framework\TestCase;

/**
 * @group Unit
 */
class EmailProviderTest extends TestCase
{
    public function testCreate(): void
    {
        $validEmail = EmailProvider::DEFAULT_MAIL;

        $email = EmailProvider::create();

        $this->assertInstanceOf(Email::class, $email);
        $this->assertSame($validEmail, (string) $email);
    }

    public function testRandom(): void
    {
        $email_1 = EmailProvider::random();
        $email_2 = EmailProvider::random();

        $this->assertIsString((string) $email_1);
        $this->assertNotEmpty((string) $email_1);

        $this->assertIsString((string) $email_2);
        $this->assertNotEmpty((string) $email_2);

        $this->assertNotSame((string) $email_1, (string) $email_2);
    }

    public function testWithEmail(): void
    {
        $validEEmail = 'my.email@test.pl';

        $email = EmailProvider::withEmail($validEEmail);

        $this->assertSame($validEEmail, (string) $email);
    }
}
