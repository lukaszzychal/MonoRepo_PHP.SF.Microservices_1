<?php

namespace App\Tests\US\Provider;

// @todo Do przeerobienia builder
interface ProviderInterface
{
    public static function defaults(): object;

    public static function create(): object;

    public static function random(): object;

    // public static function randomActualData(): object;
    // public static function many(): object;
    public static function with(int|string|object|array|null ...$value): object;
}
