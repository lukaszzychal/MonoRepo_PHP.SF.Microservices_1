<?php

declare(strict_types=1);

namespace App\NF\Infrastructure\Event;

use App\NF\Infrastructure\Factory\CommandInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;

final class CreateNotificationEvent extends RequestEvent implements CommandInterface
{
    public const NAME = 'create.notification';

    public function __construct(
        public readonly Request $request
    ) {
    }
}
