<?php

namespace App\NF\Application\Write\Handler;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class NotSuccessfullySentNotificationExceeption extends Exception
{
    public function __construct()
    {
        parent::__construct("The notification could not be sent", Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
