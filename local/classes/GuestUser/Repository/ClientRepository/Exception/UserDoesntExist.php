<?php


namespace Oip\GuestUser\Repository\ClientRepository\Exception;

use Exception;

class UserDoesntExist extends Exception
{
    public function __construct()
    {
        $message = "Guest user's write error: entity to write doesn't exist";
        parent::__construct($message, $code = 0, $previous = null);
    }
}