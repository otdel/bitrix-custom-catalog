<?php

namespace Oip\Custom\Component\Iblock;

class UFProperty
{
    const TYPE_FILE = "file";
    const TYPE_STRING = "string";
    const TYPE_HTML = "HTML";
    const TYPE_ENUMERATION = "enumeration";
    const TYPE_IBLOCK_ELEMENT= "iblock_element";
    const TYPE_DOUBLE = "double";

    /** @var int $id */
    private $id;
    /** @var int $entityId */
    private $entityId;
    /** @var string $fieldName */
    private $fieldName;
    /** @var string $userTypeId */
    private $userTypeId;
    /** @var string $xmlId */
    private $xmlId;
    /** @var int $sort */
    private $sort;
    /** @var string $multiple */
    private $multiple;
    /** @var string $mandatory */
    private $mandatory;
    /** @var string $showFilter */
    private $showFilter;
    /** @var string $showInList */
    private $showInList;
    /** @var string $editInList */
    private $editInList;
    /** @var string $isSearchable */
    private $isSearchable;
    /** @var mixed $value */
    private $value;

    /** @param array $data */
    public function __construct($data)
    {
        $this->id = $data["ID"];
        $this->entityId =  $data["ENTITY_ID"];
        $this->fieldName = $data["FIELD_NAME"];
        $this->userTypeId = $data["USER_TYPE_ID"];
        $this->xmlId = $data["XML_ID"];
        $this->sort = $data["SORT"];
        $this->multiple =  $data["MULTIPLE"];
        $this->mandatory = $data["MANDATORY"];
        $this->showFilter = $data["SHOW_FILTER"];
        $this->showInList = $data["SHOW_IN_LIST"];
        $this->editInList = $data["EDIT_IN_LIST"];
        $this->isSearchable = $data["IS_SEARCHABLE"];
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
     * @return string
     */
    public function getUserTypeId()
    {
        return $this->userTypeId;
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
     * @return int
     */
    public function getEntityId()
    {
        return $this->entityId;
    }

    /**
     * Получение данных из поля value в зависимости от типа поля
     *
     * @return mixed
     */
    public function getValue()
    {
        $getSrc = function ($value) {
            return 'upload/' . $value["SUBDIR"] . '/' . $value["FILE_NAME"];
        };

        $getText = function ($value) {
            return $value["TEXT"];
        };

        $getEnumerationText = function ($value) {
            return $value["VALUE"];
        };

        $getIblockElement = function ($value) {
            return $value["VALUE"];
        };

        switch($this->getUserTypeId()) {

            // Тип поля - "Файл"
            case self::TYPE_FILE:
                // Если файл задан
                if ($this->value != 0 && is_array($this->value)) {
                    // Если поле множественное
                    if ($this->isMultiple()) {
                        return array_map($getSrc, $this->value);
                    }
                    // Если поле единичное
                    else {
                        $value = array_shift($this->value);
                        return $getSrc($value);
                    }
                }
                else {
                    return null;
                }
            break;

            // Тип поля - "Строка"
            case self::TYPE_STRING:
                if($this->getUserTypeId() == self::TYPE_HTML) {
                    // Если поле множественное
                    if ($this->isMultiple()) {
                        $result = array_map($getText, $this->value);
                        return count($result) > 0 ? $result : null;
                    }
                    // Если поле единичное
                    else {
                        return $this->value["TEXT"];
                    }
                }
                else {
                   return $this->value ? $this->value : null;
                }
            break;

            // Тип поля - "Список"
            case self::TYPE_ENUMERATION:
                if ($this->isMultiple()) {
                    return count($this->value) > 0 ? array_map($getEnumerationText, $this->value): null;
                }
                else {
                    $value = array_shift($this->value);
                    return $value["VALUE"];
                }
            break;

            // Тип поля - "Привязка к элементу инфоблока"
            case self::TYPE_IBLOCK_ELEMENT:
                if ($this->isMultiple()) {
                    return count($this->value) > 0 ? array_map($getIblockElement, $this->value): null;
                }
                else {
                    return array_key_first($this->value);
                }
            break;

            // Тип поля - "Число"
            case self::TYPE_DOUBLE:
                return $this->value ? array_key_first($this->value)  : null;
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