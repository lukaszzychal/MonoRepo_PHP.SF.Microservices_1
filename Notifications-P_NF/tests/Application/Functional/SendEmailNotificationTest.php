<?php

namespace App\Tests\Application\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group functional
 */
class SendEmailNotificationTest extends WebTestCase
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

        $this->assertEmailCount(1);

        $email = $this->getMailerMessage();

        $this->assertEmailHtmlBodyContains($email, 'Heello - Email send');
        $this->assertEmailTextBodyContains($email, 'Heello - Email send');
    }
}
