<?php


namespace Oip\SocialStore\Order\Repository\Exception;

use Exception;
use Throwable;

class NonExsistentOrderId extends Exception
{
    public function __construct(int $orderId)
    {
        $msg = "Order %orderId% doesn't exist";
        $message = str_replace("%orderId%",$orderId, $msg);

        parent::__construct($message, $code = 0, $previous = null);
    }
}