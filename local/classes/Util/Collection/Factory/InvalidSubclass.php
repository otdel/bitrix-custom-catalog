<?php


namespace Oip\Util\Collection\Factory;

use Exception;

class InvalidSubclass extends Exception
{
    public function __construct($subclassName)
    {
        $msg = "Class %subclassName% doesn't extends Oip\Util\Collection one";

        $message = str_replace("%subclassName%", $subclassName, $msg);

        parent::__construct($message, $code = 0, $previous = null);
    }
}