<?php

namespace App\NF\Application\Write\Command;

use Symfony\Component\Serializer\Annotation\DiscriminatorMap;

#[DiscriminatorMap(typeProperty: 'type', mapping: [
    'email' => SendEmailCommand::class,
])]
abstract class TypeNotificationCommand
{
}
