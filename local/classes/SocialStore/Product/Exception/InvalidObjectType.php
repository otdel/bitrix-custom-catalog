<?php

namespace Oip\SocialStore\Product\Exception;


class InvalidObjectType extends  \Exception
{
    public function __construct($wrongType, $missingType)
    {
        $msg = "Invalid object type %wrongType% of collection. Missing %missingType%";

        $message = str_replace("%wrongType%","\"$wrongType\"", $msg);
        $message = str_replace("%missingType%", $missingType, $message);

        parent::__construct($message, $code = 0, $previous = null);
    }
}