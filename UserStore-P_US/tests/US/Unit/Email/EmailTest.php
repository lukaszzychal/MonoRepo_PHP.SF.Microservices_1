<?php

namespace App\Tests\US\Unit\Email;

use App\US\Domain\Email\Email;
use App\US\Domain\Email\InvalidEmailException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group Unit
 */
class EmailTest extends TestCase
{

    public function testCreateWithValidEmail()
    {
        $email = 'email.valid@test.pl';
        $emailVO = new Email($email);
        $this->assertSame($email, (string) $emailVO);
    }

    public function testCreateWithInValidEmail()
    {
        $email = 'email.invald@test';
        $this->expectException(InvalidEmailException::class);
        $this->expectExceptionMessage(sprintf('The email %s is not a valid email.', $email));

        new Email($email);
    }
}
