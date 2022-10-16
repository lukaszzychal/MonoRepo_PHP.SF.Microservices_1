<?php

namespace App\NF\Infrastructure\Event;

class SendNotificationEvent
{
    public const NAME = 'send.notification';

    public function __construct()
    {
    }
}
