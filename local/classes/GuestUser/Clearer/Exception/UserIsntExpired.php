<?php

namespace Oip\GuestUser\Clearer\Exception;

use Exception;

class UserIsntExpired extends Exception {
    public function __construct(int $guestId)
    {
        $message = "Deleting user $guestId isn't expired and can't be remove";
        parent::__construct($message, $code = 0, $previous = null);
    }
}