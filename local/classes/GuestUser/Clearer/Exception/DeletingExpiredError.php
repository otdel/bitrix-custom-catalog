<?php

namespace Oip\GuestUser\Clearer\Exception;

use Exception;

class DeletingExpiredError extends Exception
{
    public function __construct(int $guestId)
    {
        $message = "An error has occurred while deleting expired user $guestId";
        parent::__construct($message, $code = 0, $previous = null);
    }

}