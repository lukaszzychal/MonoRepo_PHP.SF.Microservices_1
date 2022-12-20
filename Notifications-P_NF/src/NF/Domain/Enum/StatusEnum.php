<?php

declare(strict_types=1);

namespace App\NF\Domain\Enum;

enum StatusEnum: string
{
    case CREATED = 'created';
    case WAITING_TO_BE_SENT = 'waiting_to_be_sent';
    case SENT = 'sent';
    case FAILED = 'failed';
}
