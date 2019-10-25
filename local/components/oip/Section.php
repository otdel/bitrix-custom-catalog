<?php

namespace Oip\Custom\Component\Iblock;

class Section
{
    /** @var int $id */
    private $id;
    /** @var int $iblockId */
    private $iblockId;
    /** @var int $iblockSectionId */
    private $iblockSectionId;
    /** @var string $active */
    private $active;
    /** @var string $code */
    private $code;
    /** @var int $sort */
    private $sort;
    /** @var string $name */
    private $name;
    /** @var UFProperty[] $props */
    private $props;
    /** @var Section[] $subSections */
    private $subSections;

    public function __construct($data)
    {
        $this->id = $data["ID"];
        $this->iblockId = $data["IBLOCK_ID"];
        $this->iblockSectionId = $data["IBLOCK_SECTION_ID"];
        $this->code = $data["CODE"];
        $this->name = $data["NAME"];
        $this->sort = $data["SORT"];
        $this->active = $data["ACTIVE"];

        $props = [];
        foreach($data as $propCode => $arProp) {
            // Выбираем пользовательские поля
            if (substr($propCode, 0, 3) == "UF_") {
                $props[$propCode] = new UFProperty($arProp);
            }
        }
        $this->props = $props;

        // Если есть дочерние категории
        if (isset($data["CHILDS"])) {
            foreach ($data["CHILDS"] as $child) {
                $this->subSections[] = new Section($child);
            }
        }
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
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
    public function getName()
    {
        return $this->name;
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
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @return string
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @return boolean
     */
    public function isActive()
    {
        return ($this->getActive() === "Y");
    }

    /**
     * @return int
     */
    public function getIblockSectionId()
    {
        return $this->iblockSectionId;
    }


    /** @return UFProperty[] */
    public function getProps() {
        return $this->props;
    }

    /** @return UFProperty|null */
    public function getProp($propCode) {
        return $this->getProps()[$propCode];
    }

    /**
     * @param string $propCode
     * @return string|array
     */
    public function getPropValue($propCode) {
        return ($this->getProps()[$propCode]) ? $this->getProps()[$propCode]->getValue() : "";
    }

    /**
     * @param string $propCode
     * @param int|null $key
     * @return string
     */
    public function getPropValueFromMultiple($propCode, $key = 0) {
        return ($this->getProps()[$propCode]) ? $this->getProps()[$propCode]->getValueFromMultiple($key) : "";
    }

    /**
     * @param string $propCode
     * @return string
     */
    public function getPropValueDescription($propCode) {
        return ($this->getProps()[$propCode]) ? $this->getProps()[$propCode]->getDescription() : "";
    }

    /**
     * @param string $propCode
     * @param int $key
     * @return string
     */
    public function getPropValueDescriptionFromMultiple($propCode, $key = 0) {
        return ($this->getProps()[$propCode]) ? $this->getProps()[$propCode]->getDescriptionFromMultiple($key) : "";
    }

    /**
     * @return int
     */
    public function getPropValueCount($propCode) {
        return  ($this->getProps()[$propCode]) ? $this->getProps()[$propCode]->getValueCount() : 0;
    }

    /**
     * @param string $propCode
     * @return string
     */
    public function getPropName($propCode) {
        return  ($this->getProps()[$propCode]) ? $this->getProps()[$propCode]->getName() : "";
    }

    /**
     * @return Section[]
     */
    public function getSubSections() {
        return $this->subSections;
    }
}