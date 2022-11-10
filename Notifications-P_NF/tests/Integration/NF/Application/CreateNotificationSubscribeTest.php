<?php

namespace App\Tests\Integration\NF\Application;

use App\NF\Infrastructure\Event\CreateNotificationEvent;
use App\NF\Infrastructure\Exception\InvalidParemeterRequest;
use App\NF\Infrastructure\Subscribe\CreateNotificationSubscribe;
use App\Tests\EmailNotificationTestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @group Integration
 */
class CreateNotificationSubscribeTest extends EmailNotificationTestCase
{
    private MessageBusInterface $messageBus;
    private SerializerInterface $serializer;
    private LoggerInterface $logger;
    private string $appToken;
    private CreateNotificationSubscribe $subscriber;
    private EventDispatcherInterface $dispatcher;

    protected function setUp(): void
    {
        $this->markTestSkipped(' To fix after refactory');
        $params = $this->getContainer()->get(ParameterBagInterface::class);
        $this->appToken = $params->get('app_token');
        $this->serializer = $this->getContainer()->get(SerializerInterface::class);
        $this->messageBus = $this->getContainer()->get(MessageBusInterface::class);
        $this->logger = $this->getContainer()->get(LoggerInterface::class);

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

    /**
     * @group test1
     *
     * @return void
     */
    public function testNotificationSubscribeWithCorrectTokenANDAllRRequireedDataInRequest()
    {
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

        $transport = $this->getTransport();
        $messages = $transport->getSent();

        $this->assertCount(1, $messages);

        $email = $this->getMailerMessage();
        $this->assertEmailHtmlBodyContains($email, ' text email');
        $this->assertEmailTextBodyContains($email, ' text email');
    }
}
