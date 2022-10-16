<?php

namespace App\NF\Application\Write\Handler;

use App\NF\Application\Write\Command\SendEmailCommand;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class SendEmailHandler implements MessageHandlerInterface
{
    public function __construct(
        private MailerInterface $mailer
    ) {
    }
    public function __invoke(SendEmailCommand $sendEmailCommand)
    {
        $email = (new Email())
            ->from('notification@test')
            ->to($sendEmailCommand->email)
            ->subject($sendEmailCommand->subject)
            ->html($sendEmailCommand->context)
            ->text($sendEmailCommand->context);

        $this->mailer->send($email);
    }
}
