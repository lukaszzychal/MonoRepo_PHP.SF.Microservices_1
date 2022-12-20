<?php

namespace App\NF\Domain\Model;

class EmailDetailsNotification implements DetailsNotification
{
    public function __construct(
        public string $from,
        public readonly string $to,
        public readonly string $subject,
        public readonly string $body,
    ) {
    }
}
