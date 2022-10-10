<?php

namespace App\Tests\US\Provider;

interface ProviderInterface
{
    public static function defaults(): object;
    public static function create(): object;
    public static function random(): object;
    public static function with(int|string|object|array|null ...$value): object;
}
