<?php


namespace Oip\SocialStore\Cart\Exception;

use Exception;

class UnableToCreateTheCart extends Exception
{
    public function __construct()
    {
        $message = "Creating cart error: Unable to create the cart";
        parent::__construct($message, $code = 0, $previous = null);
    }
}