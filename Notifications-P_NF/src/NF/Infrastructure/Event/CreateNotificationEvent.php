<?php

declare(strict_types=1);

namespace App\NF\Infrastructure\Event;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;

final class CreateNotificationEvent extends RequestEvent
{
    public const NAME = 'create.notification';

    public function __construct(
        public readonly Request $request
    ) {
    }
}
