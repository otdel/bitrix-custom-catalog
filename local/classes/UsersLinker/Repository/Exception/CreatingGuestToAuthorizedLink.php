<?php


namespace Oip\UsersLinker\Repository\Exception;

use Exception;

class CreatingGuestToAuthorizedLink extends Exception
{
    public function __construct(int $guestId, int $authorizedId)
    {
        $message = "An error occurred while creating link between guest $guestId and user $authorizedId";
        parent::__construct($message, $code = 0, $previous = null);
    }
}