<?php

namespace Oip\ApiService\Response\Exception;

use Exception;

class InvalidResponseStatus extends Exception
{
    public function __construct(string $status)
    {
        $message = "Некорректный $status статус для ответа API";
        parent::__construct($message, $code = 0, $previous = null);
    }
}