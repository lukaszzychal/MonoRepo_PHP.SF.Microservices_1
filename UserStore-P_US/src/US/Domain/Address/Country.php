<?php

declare(strict_types=1);

namespace App\US\Domain\Address;

enum Country: string
{
    case POLAND = 'PL';
    case UNITEED_KINGDOM = 'GB';
    case UNITED_STATES_OF_AMERICA = 'US';
}
