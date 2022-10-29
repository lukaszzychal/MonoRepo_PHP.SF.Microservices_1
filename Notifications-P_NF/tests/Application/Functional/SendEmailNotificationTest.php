<?php

namespace App\Tests\Application\Functional;

use App\NF\Application\Write\Command\SendEmailCommand;
use App\Tests\EmailNotificationTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group functional
 */
class SendEmailNotificationTest extends EmailNotificationTestCase
{
    public function testSendEmailNotificaton()
    {
        $client = $this->createClient();
        $client->request(
            'POST',
            '/notification',
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'CorrectAcceesTokenNotificationService',
            ],
            json_encode([
                'type' => 'email',
                'email' => 'my.email@test',
                'context' => 'Heello - Email send',
            ])
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $transport = $this->getTransport();
        $this->assertCount(1, $transport->getSent());
        $msq = $this->getMessageFromTransport($transport);

        $this->assertSame($msq->context, 'Heello - Email send');
        $this->assertSame($msq->email, 'my.email@test');
    }
}
