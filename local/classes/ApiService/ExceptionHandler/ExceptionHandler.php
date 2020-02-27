<?php

namespace Oip\ApiService\ExceptionHandler;

use Oip\ApiService\Response\Response;
use Oip\ApiService\Response\Status;

class ExceptionHandler
{

    public static function throwJsonException(\Throwable $ex)
    {
        die(Response::createError(Status::createError(), $ex->getMessage()));
    }

}