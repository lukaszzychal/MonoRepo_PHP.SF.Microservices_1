<?php

namespace App\US\Infrastructure\TokenRequest;

use Psr\Log\LoggerInterface;
use ReflectionClass;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;

class TokenEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly string $appToken,
        private readonly LoggerInterface $logger,
        private readonly UrlMatcherInterface $matcher
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => [
                'tokenReqest',
            ],
        ];
    }

    public function tokenReqest(RequestEvent $requestEvent): void
    {
        if (!$requestEvent->isMainRequest()) {
            return;
        }
        $request = $requestEvent->getRequest();

        if (!$this->isTokenRequired($request)) {
            return;
        }

        $headers = $request->headers;
        if (!$headers->has('authorization')) {
            $excepton = new NoAuthorizationException(
                401,
                'No authorization',
                'Please check your authorization token',
                ''
            );
            $this->logger->warning('No authorization. Please check your authorization token');
            throw $excepton;
        }
        $token = str_replace('Bearer ', '', $headers->get('authorization'));
        if ($this->appToken !== $token) {
            $excepton = new WrongAuthorizationTokenException(
                401,
                "Wrong authorization token: {$token} )",
                'Please check your authorization token',
                ''
            );
            $this->logger->warning('Wrong authorization token: ' . $token . ' endpoint: ' . $request->getBasePath());
            // throw new \Exception($excepton->toJsonResponse(), $excepton->getCode());
            throw $excepton;
        }
    }

    private function getController(Request $request): mixed
    {
        $parameters = $this->matcher->match($request->getPathInfo());
        $controllerName = explode('::', $parameters['_controller'])[0];
        if (!class_exists($controllerName)) {
            throw new WrongAuthorizationException(
                401,
                "Wrong authorization )",
                'Please check your authorization data',
                ''
            );
        }
        $controller = new ReflectionClass($controllerName);

        return $controller;
    }

    private function isTokenRequired(Request $request): bool
    {
        $controller = $this->getController($request);

        return $controller->implementsInterface(RequiredTokenRequestInterface::class);
    }
}
