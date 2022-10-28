<?php

declare(strict_types=1);

namespace App\US\Infrastructure;

use App\US\Shared\Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class ExceptionEventSubscriber implements EventSubscriberInterface
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
                'undefined error',
                $throw->getMessage(),
                ' File: '.$throw->getFile().'; Line: '.$throw->getLine()
            ),
        };

        // // @todo rodzielć na osobną meetodęę z piorytetem
        $this->logger->critical($throw->getMessage());
        throw $throw;
    }
}
