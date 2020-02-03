<?php


namespace Oip\Event\Handler\Bitrix\DataMover;

use Exception;

class GettingConfigException extends Exception
{
    public function __construct()
    {
        $message = "Config exception: failed to get movable data config. Check .settings.php file";
        parent::__construct($message, $code = 0, $previous = null);
    }
}