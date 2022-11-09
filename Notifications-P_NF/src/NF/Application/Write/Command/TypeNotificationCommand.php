<?php

declare(strict_types=1);

namespace App\NF\Application\Write\Command;

use Symfony\Component\Serializer\Annotation\DiscriminatorMap;

#[DiscriminatorMap(typeProperty: 'type', mapping: [
    'email' => CreateEmailNotifcationCommand::class,
])]
abstract class TypeNotificationCommand
{
}
