<?php

namespace App\NF\Infrastructure;

use Laminas\Code\Reflection\PropertyReflection;

interface ReadWritePropertiesExtension
{
    public function isAlwaysRead(PropertyReflection $property, string $propertyName): bool;

    public function isAlwaysWritten(PropertyReflection $property, string $propertyName): bool;

    public function isInitialized(PropertyReflection $property, string $propertyName): bool;
}
