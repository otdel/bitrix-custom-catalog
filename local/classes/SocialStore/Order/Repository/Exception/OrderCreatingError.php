<?php


namespace Oip\SocialStore\Order\Repository\Exception;

use Exception;

class OrderCreatingError extends Exception
{
    protected $message = "An error occurred while creating the order";
}