<?php

namespace Oip\GuestUser\Clearer\Exception;

use Exception;

class ArchivingRecordError extends Exception
{
    public function __construct(int $recordId)
    {
        $message = "An error has occurred while archiving record $recordId";
        parent::__construct($message, $code = 0, $previous = null);
    }
}