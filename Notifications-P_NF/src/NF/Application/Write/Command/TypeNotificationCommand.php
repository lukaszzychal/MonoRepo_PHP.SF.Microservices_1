<?php

namespace App\NF\Application\Write\Command;

use App\NF\Application\Write\Command\SendEmailCommand;
use Symfony\Component\Serializer\Annotation\DiscriminatorMap;

#[DiscriminatorMap(typeProperty: 'type', mapping: [
    'email' => SendEmailCommand::class,
])]
abstract class TypeNotificationCommand
{
}
