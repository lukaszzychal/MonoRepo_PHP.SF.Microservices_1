<?php

declare(strict_types=1);

namespace App\NF\Application\Write\Command;

use App\NF\Application\Write\ASyncCommandInterface;

final class SendEmailCommand implements ASyncCommandInterface
{
    public function __construct(
        public string $type,
        public string $email,
        public string $context,
        public ?string $subject = ''
    ) {
    }
}
