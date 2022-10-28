<?php

declare(strict_types=1);

namespace App\Tests\US\Unit;

use App\US\Infrastructure\TokenRequest\TokenEventSubscriber;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;

class TokenEventSubscriberTest
{
    public function testTokenEven()
    {
        $logger = $this->createMock(LoggerInterface::class);
        $urlMatcher = $this->createMock(UrlMatcherInterface::class);
        $subscriber = new TokenEventSubscriber(
            'token',
            $logger,
            $urlMatcher
        );

        $request = Request::create(
            '/',
            'GET',
            [],
            [],
            [],
            [
                'headeers' => [],
            ]
        );
        $httpKerner = $this->createMock(HttpKernelInterface::class);
        $eveent = new RequestEvent(
            $httpKerner,
            $request,
            HttpKernelInterface::MAIN_REQUEST
        );

        $subscriber->tokenReqest($eveent);
    }
}
