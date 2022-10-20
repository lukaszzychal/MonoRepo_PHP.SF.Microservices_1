<?php

namespace App\NF\Infrastructure;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly LoggerInterface $logger
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => [
                'exception',
            ],
        ];
    }

    public function exception(ExceptionEvent $exceptionEvent): void
    {
        if (!$exceptionEvent->isMainRequest()) {
            return;
        }

        $throw = $exceptionEvent->getThrowable();
        $exception = json_encode([
            'code' => $throw->getCode(),
            'message' => $throw->getMessage(),
            'file' => $throw->getFile(),
            'line' => $throw->getLine(),
        ]);

        // @todo rodzielć na osobną meetodęę z piorytetem
        $this->logger->critical($exception ?: 'Pusty wyjątek');

        $response = new Response($exception, $throw->getCode());
        $exceptionEvent->setResponse($response);
    }
}
