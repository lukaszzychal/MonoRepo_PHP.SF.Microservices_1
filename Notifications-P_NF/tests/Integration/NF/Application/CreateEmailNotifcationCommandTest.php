<?php

declare(strict_types=1);

namespace App\Tests\Integration\NF\Application;

use App\NF\Application\Write\Command\CreateEmailNotifcationCommand;
use App\NF\Application\Write\Handler\CreateNotificationEventHandler;
use App\NF\Domain\Enum\TypeEnum;
use App\NF\Domain\Model\Notification;
use App\NF\Domain\Repository\NotificationRepositoryInterface;
use App\Tests\EmailNotificationTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @group gg1
 */
class CreateEmailNotifcationCommandTest extends EmailNotificationTestCase
{
    public function testCreateEmailNotificationCommand(): void
    {
        $command = new CreateEmailNotifcationCommand(
            TypeEnum::EMAIL->value,
            'my.email@test',
            'Hello :)',
            'My subject'
        );

        $repo = $this->getContainer()->get(NotificationRepositoryInterface::class);
        $cbus = $this->getContainer()->get(MessageBusInterface::class);

        $handlerr = new CreateNotificationEventHandler(
            $repo,
            $cbus
        );

        $handlerr($command);

        /**
         * @var EntityManagerInterface $em
         */
        $em = $this->getContainer()->get(EntityManagerInterface::class);
        $qb = $em->createQueryBuilder();
        $qb->from(Notification::class, 'n');
        $qb->select('n');
        $result = $qb->getQuery()->getArrayResult();
        $this->assertSame($command->subject, $result[count($result) - 1]['detailsNotification']['subject']);
        $this->assertSame($command->context, $result[count($result) - 1]['detailsNotification']['body']);

        $transport = $this->getTransport();
        $this->assertCount(1, $transport->getSent());

        $email = $this->getMailerMessage();
        $this->assertEmailHtmlBodyContains($email, 'Hello :)');
    }
}
