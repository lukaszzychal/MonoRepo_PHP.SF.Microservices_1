<?php

namespace App\Tests\Application\Unit;

use App\NF\Infrastructure\ExceptionEventSubscriber;
use Exception;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @group unit, exc
 */
class ExceptionEventSubscriberTest extends TestCase
{
    private LoggerInterface $logger;
    private ExceptionEventSubscriber $subscriber;
    private EventDispatcherInterface $dispatcher;

    protected function setUp(): void
    {
        $this->logger = $this->createMock(LoggerInterface::class);

        $this->subscriber = new ExceptionEventSubscriber(
            $this->logger
        );

        $this->dispatcher = new EventDispatcher();
        $this->dispatcher->addSubscriber($this->subscriber);
    }

    public function testNotificationSubscribeInvalidRequst()
    {
        $request = Request::create(
            '/',
            'GET',
            [],
            [],
            [],
            [],
            ''
        );
        $htttpKerneel = $this->createMock(HttpKernelInterface::class);
        $event = new ExceptionEvent(
            $htttpKerneel,
            $request,
            HttpKernelInterface::MAIN_REQUEST,
            new Exception('test message', Response::HTTP_BAD_REQUEST)
        );
        $this->dispatcher->dispatch($event, KernelEvents::EXCEPTION);
        $contentJson = $event->getResponse()->getContent();
        $this->assertJson($contentJson);
        $contentArray = json_decode($contentJson, true);
        $this->assertIsArray($contentArray);
        $this->assertArrayHasKey('code', $contentArray);
        $this->assertArrayHasKey('message', $contentArray);
        $this->assertArrayHasKey('file', $contentArray);
        $this->assertArrayHasKey('line', $contentArray);

        $this->assertSame(Response::HTTP_BAD_REQUEST, $contentArray['code']);
        $this->assertSame('test message', $contentArray['message']);
    }
}
