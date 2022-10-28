<?php

declare(strict_types=1);

namespace App\Tests\US\Integration;

use App\US\Infrastructure\TokenRequest\NoAuthorizationException;
use App\US\Infrastructure\TokenRequest\TokenEventSubscriber;
use App\US\Infrastructure\TokenRequest\WrongAuthorizationTokenException;
use DateTimeImmutable;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\Uid\UuidV4;

/**
 * @group Integration
 */
class TokenEventSubscriberTest extends KernelTestCase
{
    private EventDispatcherInterface $dispatcher;
    private TokenEventSubscriber $subsriber;
    private HttpKernelInterface $httpKernel;
    private string $appToken;

    protected function setUp(): void
    {
        self::bootKernel();

        /*
         * @var EventDispatcherInterface $dispatcher
         *
         */
        $this->dispatcher = $this->getContainer()->get(EventDispatcherInterface::class);
        $logger = $this->getContainer()->get(LoggerInterface::class);
        $urlMatcher = $this->getContainer()->get(UrlMatcherInterface::class);
        $this->appToken = $this->getContainer()->getParameter('app_token');

        $this->subsriber = new TokenEventSubscriber(
            $this->appToken,
            $logger,
            $urlMatcher
        );
        $this->dispatcher->addSubscriber($this->subsriber);

        $this->httpKernel = $this->getContainer()->get(HttpKernelInterface::class);
    }

    public function testGiveNoTokenWhenTokenNotRequiredThenNoThrowExcepton()
    {
        $request = Request::create(
            '/',
            'GET',
            [],
            [],
            [],
            []
        );
        $event = $this->createRequestEvent($request);

        $this->expectNotToPerformAssertions();
        $this->dispatcher->dispatch($event, KernelEvents::REQUEST);
    }

    public function testGiveNoTokenWhenTokenRequiredThenThrowExcepton()
    {
        $request = Request::create(
            '/users',
            'POST',
            [],
            [],
            [],
            []
        );

        $event = $this->createRequestEvent($request);

        $this->expectException(NoAuthorizationException::class);
        $this->dispatcher->dispatch($event, KernelEvents::REQUEST);
    }

    public function testGiveWrongTokenWhenTokenRequiredThenThrowExcepton()
    {
        $request = Request::create(
            '/users',
            'POST',
            [],
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'WrongToken',
            ],
        );

        $event = $this->createRequestEvent($request);

        $this->expectException(WrongAuthorizationTokenException::class);
        $this->dispatcher->dispatch($event, KernelEvents::REQUEST);
    }

    public function testGiveCorrectTokenWhenTokenRequiredThenNoThrowExcepton()
    {
        $request = Request::create(
            '/users',
            'POST',
            [],
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->appToken,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            json_encode([
                'uuid' => UuidV4::v4(),
                'firstName' => 'Łukasz',
                'lastName' => 'Z',
                'email' => 'email.testowy@test.pl',
                'address' => [],
                'birthDate' => (new DateTimeImmutable())->format('d:m:Y'),
                'avatar' => '',
            ])
        );

        $event = $this->createRequestEvent($request);

        $this->expectNotToPerformAssertions();
        $this->dispatcher->dispatch($event, KernelEvents::REQUEST);
    }

    private function createRequestEvent(Request $request): RequestEvent
    {
        $event = new RequestEvent(
            $this->httpKernel,
            $request,
            HttpKernelInterface::MAIN_REQUEST
        );

        return $event;
    }
}
