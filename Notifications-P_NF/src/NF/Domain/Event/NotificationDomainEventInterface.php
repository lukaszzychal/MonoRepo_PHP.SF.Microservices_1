<?php

namespace App\NF\Domain\Event;

use App\NF\Domain\Enum\StatusEnum;
use App\NF\Domain\Enum\TypeEnum;
use App\NF\Domain\Model\DetailsNotification;
use Symfony\Component\Uid\Uuid;

interface NotificationDomainEventInterface extends DomainEventInterface
{
    public function getId(): Uuid;
    public function getType(): TypeEnum;
    public function getStatus(): StatusEnum;
    public function getDetails(): DetailsNotification;
}
