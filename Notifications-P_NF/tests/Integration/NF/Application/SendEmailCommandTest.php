<?php

declare(strict_types=1);

namespace App\Tests\Integration\NF\Application;

use App\NF\Application\Write\Command\SendEmailCommand;
use App\NF\Application\Write\Handler\SendEmailHandler;
use App\NF\Domain\Enum\TypeEnum;
use App\Tests\EmailNotificationTestCase;
use Symfony\Component\Mailer\MailerInterface;

/**
 * @group integration
 * @group group11
 */
class SendEmailCommandTest extends EmailNotificationTestCase
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

        $transport = $this->getTransport();
        $this->assertCount(1, $transport->getSent());

        $email = $this->getMailerMessage();
        $this->assertEmailHtmlBodyContains($email, 'Hello :)');
        $this->assertEmailTextBodyContains($email, 'Hello :)');
    }
}
