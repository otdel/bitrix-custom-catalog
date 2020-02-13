<?php

namespace Oip\GuestUser\Clearer\Exception;

use Exception;

class UserDoesntExist extends Exception {
    public function __construct(int $guestId)
    {
        $message = "Deleting user $guestId doesn't exist";
        parent::__construct($message, $code = 0, $previous = null);
    }
}