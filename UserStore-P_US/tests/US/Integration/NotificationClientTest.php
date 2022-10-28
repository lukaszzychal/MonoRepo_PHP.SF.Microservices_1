<?php

declare(strict_types=1);

namespace App\Tests\US\Integration;

use App\Tests\US\Provider\User\UserProvider;
use App\US\Infrastructure\Client\Notification\NotificationClient;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpClient\Response\MockResponse;

/**
 * @group Integraton
 */
class NotificationClientTest extends KernelTestCase
{
    public function testNotificatonClient(): void
    {
        self::bootKernel();

        $fakeClient = new FakeHttpClient([new FakeHttpResponse(
            '/notification',
            'POST',
            new MockResponse('Wysłano powiadomminie', ['http_code' => 200])
        )]);
        /**
         * @var LoggerInterface
         */
        $logger = $this->getContainer()->get(LoggerInterface::class);

        /**
         * @var ContainerBagInterface
         */
        $parameterBag = $this->getContainer()->get(ContainerBagInterface::class);

        $client = new NotificationClient(
            $fakeClient,
            $logger,
            $parameterBag
        );

        $user = UserProvider::random();
        $reesponse = $client->sendEmail(
            $user->getEmail(),
            'Subject: Notfication',
            'Context: Created User'
        );

        $this->assertSame(200, $reesponse->getStatusCode());
        $this->assertSame('Wysłano powiadomminie', $reesponse->getContent());
    }
}
