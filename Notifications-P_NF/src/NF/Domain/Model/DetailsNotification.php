<?php

namespace App\NF\Domain\Model;

use Symfony\Component\Serializer\Annotation\DiscriminatorMap;

#[DiscriminatorMap(typeProperty: 'type', mapping: [
    'email' => EmailDetailsNotification::class
])]
interface DetailsNotification
{
}
