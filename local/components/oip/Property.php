<?php

namespace Oip\Custom\Component\Iblock;

class Property
{

    const TYPE_FILE = "F";
    const TYPE_STRING = "S";
    const USER_TYPE_HTML = "HTML";

    /** @var int $id */
    private $id;
    /** @var int $iblockId */
    private $iblockId;
    /** @var int $linkIblockId */
    private $linkIblockId;
    /** @var string $name */
    private $name;
    /** @var string $code */
    private $code;
    /** @var string $type */
    private $type;
    /** @var string $userType */
    private $userType;
    /** @var string $multiple */
    private $multiple;
    /** @var mixed $description */
    private $description;
    /** @var mixed $value */
    private $value;

    /** @param array $data */
    public function __construct($data)
    {
        $this->id = $data["ID"];
        $this->iblockId =  $data["IBLOCK_ID"];
        $this->linkIblockId =  ($data["PROPERTY_TYPE"]) ? (int)$data["LINK_IBLOCK_ID"] : 0;
        $this->name = $data["NAME"];
        $this->code = $data["CODE"];
        $this->type = $data["PROPERTY_TYPE"];
        $this->userType = $data["USER_TYPE"];
        $this->multiple =  $data["MULTIPLE"];
        $this->description = $data["DESCRIPTION"];
        $this->value = ($data["PROPERTY_TYPE"] == self::TYPE_STRING
            && $data["USER_TYPE"] == self::USER_TYPE_HTML) ? $data["~VALUE"] : $data["VALUE"];
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
     * @return int
     */
    public function getLinkIblockId()
    {
        return $this->linkIblockId;
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
    public function getUserType()
    {
        return $this->userType;
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
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param int $key
     * @return string
     */
    public function getDescriptionFromMultiple($key = 0) {
        return ($this->isMultiple()) ? $this->getDescription()[$key] : $this->getDescription();
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        $getSrc = function ($value) {
            return $value["src"];
        };

        $getText = function ($value) {
            return $value["TEXT"];
        };

        switch($this->getType()) {
            case self::TYPE_FILE:
                return ($this->isMultiple()) ? array_map($getSrc, $this->value) : $this->value["src"];
            break;

            case self::TYPE_STRING:
                if($this->userType == self::USER_TYPE_HTML) {
                    return ($this->isMultiple()) ? array_map($getText, $this->value) : $this->value["TEXT"];
                }
                else {
                   return $this->value;
                }
            break;

            default:
                return $this->value;
            break;
        }
    }

    /**
     * @param int $key
     * @return string
     */
    public function getValueFromMultiple($key = 0) {
        return ($this->isMultiple()) ? $this->getValue()[$key] : $this->getValue();
    }

    /**
     * @return int
     */
    public function getValueCount() {
        return count($this->getValue());
    }

}