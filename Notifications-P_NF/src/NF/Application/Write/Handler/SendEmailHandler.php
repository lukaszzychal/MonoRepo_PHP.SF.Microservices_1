<?php

declare(strict_types=1);

namespace App\NF\Application\Write\Handler;

use App\NF\Application\Write\Command\SendEmailCommand;
use DateTimeImmutable;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

final class SendEmailHandler implements MessageHandlerInterface
{
    public function __construct(
        private MailerInterface $mailer
    ) {
    }

    public function __invoke(SendEmailCommand $sendEmailCommand): void
    {
        $context = $sendEmailCommand->context . ' <h4>This mail send: ' . (new DateTimeImmutable())->format('d.m.Y h:m:s') . '</h4>';
        $email = (new Email())
            ->from('notification@test')
            ->to($sendEmailCommand->email)
            ->subject($sendEmailCommand->subject ?? 'Default Subject')
            ->html($context)
            ->text($context);

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            throw new NotSuccessfullySentNotificationExceeption();
        }
    }
}
