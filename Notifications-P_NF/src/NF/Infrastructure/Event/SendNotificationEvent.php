<?php

namespace App\NF\Infrastructure\Event;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class SendNotificationEvent extends RequestEvent
{
    public const NAME = 'send.notification';

    public function __construct(
        public readonly Request $request
    ) {
    }
}
