<?php

namespace App\US\Infrastructure;

use App\US\Shared\Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        dump('----');
        dump($throw->getMessage());
        dump(get_class(
            $throw
        ));
        $exception = match ($throw->getCode()) {
                //     400 => new Exception(
                //         $throw->getCode(),
                //         'Bad Request',
                //         'Wrong request. Please check again',
                //         ''
                //     ),
                //     401 => new Exception(
                //         $throw->getCode(),
                //         'Unauthorized',
                //         'Wrong client credentials',
                //         ''
                //     ),
                //     404 => new Exception(
                //         $throw->getCode(),
                //         'Not Found',
                //         'Not found, maybe it doesn’t exist',
                //         ''
                //     ),
            default => new Exception(
                $throw->getCode(),
                $throw->getFile(),
                $throw->getMessage(),
                $throw->getLine()
            ),
        };

        // @todo rodzielć na osobną meetodęę z piorytetem
        $this->logger->critical($throw->getMessage());

        $jsonResponse = new JsonResponse($exception->toArray(), $exception->getCode());
        $exceptionEvent->setResponse($jsonResponse);
    }
}
