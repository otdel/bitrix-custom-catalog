<?php

namespace Oip\ProductFeature;

/**
 * Class ProductFeature
 * Характеристика товара и ее описание
 *
 * @package Oip\ProductFeature
 */
class ProductFeature
{
    /** @var int $id Идентификатор характеристики */
    private $id;
    /** @var string $code Символьный код характеристики */
    private $code;
    /** @var string $name Название характеристики (заголовок) */
    private $name;
    /** @var int $sortFilter Порядок сортировки в списке фильтров */
    private $sortFilter;
    /** @var int $sortInfo Порядок сортировки в информации о товаре (например, в деталке) */
    private $sortInfo;
    /** @var string $cssFilterClassname Имя CSS класса для вывода в списке фильтров */
    private $cssFilterClassname;
    /** @var bool $isFilter Возможно ли сортировать по данному полю? 0 - Нет, 1 - Можно */
    private $isFilter;
    /** @var bool $isPredefined Является ли значение поля одним из ранее предустановленых или можно ввести вручную */
    private $isPredefined;
    /** @var bool $isDisabled Признак отключения (мягкого удаления) характеристики */
    private $isDisabled;
    /** @var int $dateInsert Дата добавления записи в таблицу (timestamp) */
    private $dateInsert;
    /** @var int $dateModify Дата последнего изменения записи (timestamp) */
    private $dateModify;

    /**
     * ProductFeature constructor.
     * @param array $arParams
     */
    public function __construct($arParams)
    {
        if (isset($arParams['id'])) $this->id = $arParams['id'];
        if (isset($arParams['code'])) $this->code = $arParams['code'];
        if (isset($arParams['name'])) $this->name = $arParams['name'];
        if (isset($arParams['sortFilter'])) $this->sortFilter = $arParams['sortFilter'];
        if (isset($arParams['sortInfo'])) $this->sortInfo = $arParams['sortInfo'];
        if (isset($arParams['cssFilterClassname'])) $this->cssFilterClassname = $arParams['cssFilterClassname'];
        if (isset($arParams['isFilter'])) $this->isFilter = $arParams['isFilter'];
        if (isset($arParams['isPredefined'])) $this->isPredefined = $arParams['isPredefined'];
        if (isset($arParams['isDisabled'])) $this->isDisabled = $arParams['isDisabled'];
        if (isset($arParams['dateInsert'])) $this->dateInsert = $arParams['dateInsert'];
        if (isset($arParams['dateModify'])) $this->dateModify = $arParams['dateModify'];
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId(int $id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return $this
     */
    public function setCode(string $code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return int
     */
    public function getSortFilter()
    {
        return isset($this->sortFilter) ? $this->sortFilter : 100;
    }

    /**
     * @param int $sortFilter
     * @return $this
     */
    public function setSortFilter($sortFilter)
    {
        $this->sortFilter = $sortFilter;
        return $this;
    }

    /**
     * @return int
     */
    public function getSortInfo()
    {
        return isset($this->sortInfo) ? $this->sortInfo : 100;
    }

    /**
     * @param int $sortInfo
     * @return $this
     */
    public function setSortInfo($sortInfo)
    {
        $this->sortInfo = $sortInfo;
        return $this;
    }

    /**
     * @return string
     */
    public function getCssFilterClassname()
    {
        return isset($this->cssFilterClassname) ? $this->cssFilterClassname : "";
    }

    /**
     * @param string $cssFilterClassname
     * @return $this
     */
    public function setCssFilterClassname($cssFilterClassname)
    {
        $this->cssFilterClassname = $cssFilterClassname;
        return $this;
    }

    /**
     * @return bool
     */
    public function getIsFilter()
    {
        return $this->isFilter;
    }

    /**
     * @param bool $isFilter
     * @return $this
     */
    public function setIsFilter($isFilter)
    {
        $this->isFilter = $isFilter;
        return $this;
    }

    /**
     * @return bool
     */
    public function getIsPredefined()
    {
        return $this->isPredefined;
    }

    /**
     * @param bool $isPredefined
     * @return $this
     */
    public function setIsPredefined(bool $isPredefined)
    {
        $this->isPredefined = $isPredefined;
        return $this;
    }

    /**
     * @return bool
     */
    public function getIsDisabled()
    {
        return $this->isDisabled;
    }

    /**
     * @param bool $isDisabled
     * @return $this
     */
    public function setIsDisabled(bool $isDisabled)
    {
        $this->isDisabled = $isDisabled;
        return $this;
    }

    /**
     * @return int
     */
    public function getDateInsert()
    {
        return $this->dateInsert;
    }

    /**
     * @param int $dateInsert
     * @return $this
     */
    public function setDateInsert(int $dateInsert)
    {
        $this->dateInsert = $dateInsert;
        return $this;
    }

    /**
     * @return int
     */
    public function getDateModify()
    {
        return $this->dateModify;
    }

    /**
     * @param int $dateModify
     * @return $this
     */
    public function setDateModify(int $dateModify)
    {
        $this->dateModify = $dateModify;
        return $this;
    }

}