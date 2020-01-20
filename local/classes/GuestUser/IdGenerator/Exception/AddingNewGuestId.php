<?php


namespace Oip\GuestUser\IdGenerator\Exception;

use Exception;

class AddingNewGuestId extends Exception
{
    public function __construct()
    {
        $message = "An error occurred while adding a new user";
        parent::__construct($message, $code = 0, $previous = null);
    }
}