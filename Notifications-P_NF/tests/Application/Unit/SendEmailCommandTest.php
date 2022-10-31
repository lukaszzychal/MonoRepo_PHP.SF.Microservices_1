<?php

namespace App\Tests\Application\Unit;

use App\NF\Application\Write\Command\SendEmailCommand;
use App\NF\Application\Write\Handler\SendEmailHandler;
use App\NF\Domain\Enum\TypeEnum;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mailer\MailerInterface;

class SendEmailCommandTest extends TestCase
{
    public function testSendEEmail(): void
    {
        $command = new SendEmailCommand(
            TypeEnum::EMAIL->value,
            'my.email@test',
            'Hello :) ',
            'My subject'
        );

        $mailerMock = $this->createMock(MailerInterface::class);
        $mailerMock->expects($this->once())
            ->method('send');

        $handlerr = new SendEmailHandler(
            $mailerMock
        );

        $handlerr($command);
    }
}
