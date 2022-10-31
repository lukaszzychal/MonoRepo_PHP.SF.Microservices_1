<?php

declare(strict_types=1);

namespace App\NF\Application\Write\Command;

final class CreateNotifcationCommand implements CommandInterface
{
    public function __construct(
        public string $type,
        public string $email,
        public string $context,
        public ?string $subject = ''
    ) {
    }
}
