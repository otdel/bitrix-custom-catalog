<?php

namespace Oip\SocialStore\Cart\Exception;


class ItemDoesntExist extends \Exception
{
    public function __construct($userId, $productId)
    {
        $msg = "Cart item with user_id %userId% and product_id %productId% hasn't existed";

        $message = str_replace("%userId%", $userId, $msg);
        $message = str_replace("%productId%", $productId, $message);

        parent::__construct($message, $code = 0, $previous = null);
    }
}