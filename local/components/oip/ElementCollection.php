<?php

namespace Oip\Custom\Component\Iblock;

class ElementCollection implements \Countable
{

    private $elements;

    /** @param Element[] */
    public function __construct($elements)
    {
        $this->index = 0;
        $this->elements = $elements;
    }

    /**
     * @return Element[]
     */
    public function getElements()
    {
        return $this->elements;
    }

    /**
     * @return Element
     */
    public function getElement($elementID)
    {
        return $this->elements[$elementID];
    }

    public function count()
    {
        return count($this->elements);
    }


}