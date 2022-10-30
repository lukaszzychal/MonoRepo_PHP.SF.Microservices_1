<?php

declare(strict_types=1);

namespace App\NF\Domain\Enum;

enum StatusEnum: string
{
    case CREATE = 'create';
    case SENT = 'sent';
    case FAILED = 'failed';
}
