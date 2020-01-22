<?php

namespace Oip\Util\Serializer\ObjectSerializer;


interface SerializerInterface
{
    public function serialize($object);
    public function deserialize($string);
}