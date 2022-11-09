<?php

declare(strict_types=1);

namespace App\NF\Application\Write\Handler;

use Symfony\Component\HttpFoundation\Response;

final class NotSuccessfullySentNotificationExceeption extends \Exception
{
    public function __construct()
    {
        parent::__construct('The notification could not be sent', Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
