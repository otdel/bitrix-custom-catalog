<?php

namespace Oip\Util;


abstract class Collection implements \Iterator, \Countable
{
    /** @var array $values */
    protected $values;

    public function current()
    {
        return current($this->values);
    }

    public function next()
    {
        return next($this->values);
    }

    public function key()
    {
        return key($this->values);
    }

    public function valid()
    {
        return ($this->key() !== null);
    }

    public function rewind()
    {
        return reset($this->values);
    }

    public function count()
    {
        return count($this->values);
    }

    /** @return array */
    public function getArray() {
        return $this->values;
    }

}