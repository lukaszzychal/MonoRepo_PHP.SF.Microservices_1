<?php

declare(strict_types=1);

namespace App\Tests\Unit\NF\Application;

use App\NF\Application\Write\Command\SendEmailCommand;
use App\NF\Infrastructure\Event\CreateNotificationEvent;
use App\NF\Infrastructure\Exception\InvalidParemeterRequest;
use App\NF\Infrastructure\Subscribe\CreateNotificationSubscribe;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @group unit
 */
class CreateNotificationSubscribeTest extends TestCase
{
    private MockObject|MessageBusInterface $messageBus;
    private MockObject|SerializerInterface $serializer;
    private LoggerInterface $logger;
    private string $appToken;
    private CreateNotificationSubscribe $subscriber;
    private EventDispatcherInterface $dispatcher;

    protected function setUp(): void
    {
        $this->appToken = 'CorrectAcceesTokenNotificationService';
        $this->serializer = $this->createMock(SerializerInterface::class);
        $this->messageBus = $this->createMock(MessageBusInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);

        $this->subscriber = new CreateNotificationSubscribe(
            $this->appToken,
            $this->serializer,
            $this->messageBus,
            $this->logger
        );

        $this->dispatcher = new EventDispatcher();
        $this->dispatcher->addSubscriber($this->subscriber);
    }

    public function testNotificationSubscribeInvalidRequst()
    {
        $this->expectException(InvalidParemeterRequest::class);
        $this->expectExceptionMessage('Invalid paremeter [ Authorization ] request');

        $request = Request::create(
            '/',
            'GET',
            [],
            [],
            [],
            [],
            ''
        );

        $event = new CreateNotificationEvent($request);
        $this->dispatcher->dispatch($event, CreateNotificationEvent::NAME);
    }

    public function testNotificationSubscribeWitoutTokenInRequest()
    {
        $this->expectException(InvalidParemeterRequest::class);
        $this->expectExceptionMessage('Invalid paremeter [ Authorization ] request');

        $request = Request::create(
            '/notification',
            'POST',
            [],
            [],
            [],
            [],
            json_encode([
                'test' => 'test',
            ])
        );

        $event = new CreateNotificationEvent($request);
        $this->dispatcher->dispatch($event, CreateNotificationEvent::NAME);
    }

    public function testNotificationSubscribeWithWrongTokenANDWithoutTypeInRequest()
    {
        $this->expectException(InvalidParemeterRequest::class);
        $this->expectExceptionMessage('Invalid paremeter [ Type ] request');

        $request = Request::create(
            '/notification',
            'POST',
            [],
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'wrong token',
            ],
            json_encode([
                'test' => 'test',
            ])
        );

        $event = new CreateNotificationEvent($request);
        $this->dispatcher->dispatch($event, CreateNotificationEvent::NAME);
    }

    public function testNotificationSubscribeWithWrongTokenANDWithTypeInRequest()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Wrong token');

        $request = Request::create(
            '/notification',
            'POST',
            [],
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'WrongToken',
            ],
            json_encode([
                'type' => 'email',
            ])
        );

        $event = new CreateNotificationEvent($request);
        $this->dispatcher->dispatch($event, CreateNotificationEvent::NAME);
    }

    public function testNotificationSubscribeWithCorrectTokenANDAllRRequireedDataInRequest()
    {
        $emailCommand = new SendEmailCommand(
            'email',
            'my.email@test',
            'text email',
            ' subject email'
        );
        $this->serializer->expects($this->once())->method('deserialize')->willReturn($emailCommand);

        $this->messageBus->expects($this->once())
            ->method('dispatch')
            ->with(
                self::isInstanceOf(SendEmailCommand::class)
            )
            ->willReturn(new Envelope($emailCommand));

        $request = Request::create(
            '/notification',
            'POST',
            [],
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->appToken,
            ],
            json_encode([
                'type' => 'email',
                'email' => 'my.email@test',
                'context' => ' text email',
            ])
        );

        $event = new CreateNotificationEvent($request);
        $this->dispatcher->dispatch($event, CreateNotificationEvent::NAME);
    }
}
