<?php

namespace Oip\Custom\Component\Iblock;

class Property
{
    /** @var int $id */
    private $id;
    /** @var int $iblockId */
    private $iblockId;
    /** @var string $name */
    private $name;
    /** @var string $code */
    private $code;
    /** @var string $type */
    private $type;
    /** @var string $multiple */
    private $multiple;
    /** @var mixed $value */
    private $value;

    /** @param array $data */
    public function __construct(&$data)
    {
        $this->id = $data["ID"];
        $this->iblockId =  $data["IBLOCK_ID"];
        $this->name = $data["NAME"];
        $this->code = $data["CODE"];
        $this->type = $data["PROPERTY_TYPE"];
        $this->multiple =  $data["MULTIPLE"];
        $this->value = $data["VALUE"];
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getIblockId()
    {
        return $this->iblockId;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getMultiple()
    {
        return $this->multiple;
    }

    /**
     * @return bool
     */
    public function isMultiple()
    {
        return ($this->getMultiple() === "Y");
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }



}