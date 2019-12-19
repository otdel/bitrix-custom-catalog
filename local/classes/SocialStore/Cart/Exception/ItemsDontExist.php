<?php

namespace Oip\SocialStore\Cart\Exception;


class ItemsDontExist extends \Exception
{
    public function __construct($userId)
    {
        $msg = "Cart items with user_id %userId% hasn't existed";

        $message = str_replace("%userId%", $userId, $msg);

        parent::__construct($message, $code = 0, $previous = null);
    }
}