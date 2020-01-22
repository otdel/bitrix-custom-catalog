<?php

namespace Oip\GuestUser\Repository\ServerRepository\Exception;

use Exception;

class GettingByHashId extends  Exception
{
    public function __construct($hashId)
    {
        $message = "User with hashId $hashId doesn't exist";
        parent::__construct($message, $code = 0, $previous = null);
    }
}