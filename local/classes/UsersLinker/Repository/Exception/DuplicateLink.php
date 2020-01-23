<?php


namespace Oip\UsersLinker\Repository\Exception;

use Exception;

class DuplicateLink extends Exception
{
    public function __construct(int $guestId, int $authorizedId)
    {
        $message = "A link between guest $guestId and user $authorizedId already exists";
        parent::__construct($message, $code = 0, $previous = null);
    }
}