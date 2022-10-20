<?php

namespace App\Tests\Application\Integration;

use App\NF\Application\Write\Command\SendEmailCommand;
use App\NF\Application\Write\Handler\SendEmailHandler;
use App\NF\Infrastructure\Enum\TypeEnum;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Mailer\MailerInterface;

/**
 * @group integration
 */
class SendEmailCommandTest extends KernelTestCase
{
    public function testSendEEmail(): void
    {
        $command = new SendEmailCommand(
            TypeEnum::EMAIL->value,
            'my.email@test',
            'Hello :)',
            'My subject'
        );

        $mailer = $this->getContainer()->get(MailerInterface::class);

        $handlerr = new SendEmailHandler(
            $mailer
        );

        $handlerr($command);

        $this->assertEmailCount(1);

        $email = $this->getMailerMessage();

        $this->assertEmailHtmlBodyContains($email, 'Hello :)');
        $this->assertEmailTextBodyContains($email, 'Hello :)');
    }
}
