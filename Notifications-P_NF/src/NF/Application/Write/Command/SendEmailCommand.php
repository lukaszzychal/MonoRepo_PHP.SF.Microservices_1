<?php

namespace App\NF\Application\Write\Command;

class SendEmailCommand implements CommandInterface
{
    public function __construct(
        public string $type,
        public string $email,
        public string $context,
        public ?string $subject = ''
    ) {
    }
}
