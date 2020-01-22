<?php

namespace Oip\Util\Serializer\ObjectSerializer;

class Base64Serializer implements SerializerInterface
{
    public function serialize($object)
    {
        return base64_encode(serialize($object));
    }

    public function deserialize($string)
    {
        return unserialize(base64_decode($string));
    }

}