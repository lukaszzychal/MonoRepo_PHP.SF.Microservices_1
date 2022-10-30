<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Mailer\Messenger\SendEmailMessage;
use Symfony\Component\Messenger\Transport\InMemoryTransport;

class EmailNotificationTestCase extends WebTestCase
{
    protected function getTransport(): InMemoryTransport
    {
        /** @var InMemoryTransport $transport */
        $transport = $this->getContainer()->get('messenger.transport.async');

        return $transport;
    }

    protected function getMessageFromTransport(InMemoryTransport $transport): SendEmailMessage
    {
        $envelopes = $transport->get();
        /**
         * @var SendEmailMessage $msq
         */
        $msq = $envelopes[0]->getMessage();

        return $msq;
    }
}
