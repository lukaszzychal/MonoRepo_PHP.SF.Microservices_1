<?php

declare(strict_types=1);

namespace App\NF\Application\Write\Command;

final class CreateEmailNotifcationCommand implements CommandInterface
{
    public function __construct(
        public string $type,
        public string $email,
        public string $context,
        public ?string $subject = ''
    ) {
    }
}
