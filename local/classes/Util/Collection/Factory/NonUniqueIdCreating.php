<?php

namespace Oip\Util\Collection\Factory;

use Exception;

class NonUniqueIdCreating extends Exception
{
    public function __construct($id, $collectionName)
    {
        $msg = "Non-unique id %id% creating object collection %collectionName%";

        $message = str_replace("%id%","\"$id\"", $msg);
        $message = str_replace("%collectionName%", $collectionName, $message);

        parent::__construct($message, $code = 0, $previous = null);
    }
}