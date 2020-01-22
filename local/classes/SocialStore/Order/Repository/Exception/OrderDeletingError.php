<?php


namespace Oip\SocialStore\Order\Repository\Exception;

use Exception;

class OrderDeletingError extends Exception
{
    public function __construct(int $orderId)
    {
        $msg = "An error occurred while creating the order %orderId%";
        $message = str_replace("%orderId%",$orderId, $msg);

        parent::__construct($message, $code = 0, $previous = null);
    }
}