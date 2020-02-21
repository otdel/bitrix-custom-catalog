<?php

namespace Oip\ApiService\ExceptionHandler;

use Oip\ApiService\Response\Response;

class ExceptionHandler
{

    public static function throwJsonException(\Throwable $ex)
    {
        die(new Response("error", null, $ex->getMessage()));
    }

}