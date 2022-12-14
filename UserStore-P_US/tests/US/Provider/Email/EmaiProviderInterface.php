<?php

declare(strict_types=1);

namespace App\Tests\US\Provider\Email;

use App\Tests\US\Provider\ProviderInterface;

interface EmaiProviderInterface extends ProviderInterface
{
    public static function withEmail(string $email): object;
}
