<?php

declare(strict_types=1);

namespace App\Tests\US\Unit;

use App\Tests\US\Provider\User\UserProvider;
use App\US\Domain\User\User;
use App\US\Infrastructure\Client\Notification\NotificationClient;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * @group unit
 * @group unc
 */
class NotificationClientTest extends TestCase
{
    /**
     * @var HttpClientInterface|MockObject
     */
    private HttpClientInterface $httpClient;

    /**
     * @var LoggerInterface|MockObject
     */
    private LoggerInterface $logger;

    /**
     * @var ContainerBagInterface|MockObject
     */
    private ContainerBagInterface $parameterBag;

    private User $user;

    private string $context;
    private string $subject;

    protected function setUp(): void
    {
        // $this->httpClient = new MockHttpClient(new MockResponse("test"), 'https://localhost');
        $this->httpClient = $this->createMock(HttpClientInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->parameterBag = $this->createMock(ContainerBagInterface::class);
        $this->user = UserProvider::create();
        $this->context = 'Hi. You accont was created. :) Welcome :)';
        $this->subject = 'User was creeated';
        parent::setUp();
    }

    public function testNotificatonClient(): void
    {
        $this->parameterBag
            ->expects($this->exactly(2))
            ->method('has')
            ->with($this->logicalOr(
                'nfs_host',
                'nfs_token'
            ))
            ->will($this->returnCallback(function ($value) {
                return true;
            }));

        $this->parameterBag
            ->expects($this->exactly(2))
            ->method('get')
            ->with($this->logicalOr(
                'nfs_host',
                'nfs_token'
            ))
            ->will(
                $this->returnCallback(
                    function (string $type) {
                        return match ($type) {
                            'nfs_host' => 'localhost',
                            'nfs_token' => 'token'
                        };
                    }
                )
            );

        $this->httpClient->expects(self::once())->method('request');

        $client = new NotificationClient(
            $this->httpClient,
            $this->logger,
            $this->parameterBag
        );

        $client->sendEmail(
            $this->user->getEmail(),
            $this->subject,
            $this->context
        );
    }

    public function testNotificatonClientThrowExceeption(): void
    {
        $this->parameterBag
            ->expects($this->exactly(2))
            ->method('has')
            ->with($this->logicalOr(
                'nfs_host',
                'nfs_token'
            ))
            ->will($this->returnCallback(function ($value) {
                return true;
            }));

        $this->parameterBag
            ->expects($this->exactly(2))
            ->method('get')
            ->with($this->logicalOr(
                'nfs_host',
                'nfs_token'
            ))
            ->will(
                $this->returnCallback(
                    function (string $type) {
                        return match ($type) {
                            'nfs_host' => 'localhost',
                            'nfs_token' => 'token'
                        };
                    }
                )
            );

        /*
         * @var HttpKernelInterface|MockObject
         */
        $this->httpClient
            ->expects(self::once())
            ->method('request')
            ->willThrowException(new Exception());

        $client = new NotificationClient(
            $this->httpClient,
            $this->logger,
            $this->parameterBag
        );

        $this->expectException(Exception::class);

        $client->sendEmail(
            $this->user->getEmail(),
            $this->subject,
            $this->context
        );
    }
}
