<?php

namespace Oip\ProductFeature;

/**
 * Class SectionFeatureOption
 * Настройки характеристик внутри раздела
 *
 * @package Oip\ProductFeature
 */
class SectionFeatureOption
{
    /** @var int $id Идентификатор характеристики */
    private $id;
    /** @var int $sectionId Идентификатор раздела */
    private $sectionId;
    /** @var string $featureCode Код характеристики */
    private $featureCode;
    /** @var string $featureName Название характеристики */
    private $featureName;
    /** @var bool $isFilter Флаг - можно ли фильтровать по данному полю */
    private $isFilter;
    /** @var bool $isInfo Флаг - выводить ли характеристику в информации о товаре */
    private $isInfo;
    /** @var bool $isDisabled Флаг мягкого удаления настройки */
    private $isDisabled;
    /** @var int $sortFilter Порядок вывода характеристики в списке фильтров */
    private $sortFilter;
    /** @var int $sortInfo Порядок вывода характеристики в информации о товаре */
    private $sortInfo;
    /** @var \DateTime $dateInsert Дата добавления настройки в таблицу */
    private $dateInsert;
    /** @var \DateTime $dateModify Дата последнего изменения настройки в таблице  */
    private $dateModify;

    /**
     * ProductFeature constructor.
     * @param array $arParams
     */
    public function __construct($arParams)
    {
        if (isset($arParams['id'])) $this->id = $arParams['id'];
        if (isset($arParams['sectionId'])) $this->sectionId = $arParams['sectionId'];
        if (isset($arParams['featureCode'])) $this->featureCode = $arParams['featureCode'];
        if (isset($arParams['featureName'])) $this->featureName = $arParams['featureName'];
        if (isset($arParams['isFilter'])) $this->isFilter = $arParams['isFilter'];
        if (isset($arParams['isInfo'])) $this->isInfo = $arParams['isInfo'];
        if (isset($arParams['isDisabled'])) $this->isDisabled = $arParams['isDisabled'];
        if (isset($arParams['sortFilter'])) $this->sortFilter = $arParams['sortFilter'];
        if (isset($arParams['sortInfo'])) $this->sortInfo = $arParams['sortInfo'];
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
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getSectionId()
    {
        return $this->sectionId;
    }

    /**
     * @param int $sectionId
     */
    public function setSectionId($sectionId)
    {
        $this->sectionId = $sectionId;
        return $this;
    }

    /**
     * @return string
     */
    public function getFeatureCode()
    {
        return $this->featureCode;
    }

    /**
     * @param string $featureCode
     */
    public function setFeatureCode($featureCode)
    {
        $this->featureCode = $featureCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getFeatureName()
    {
        return $this->featureName;
    }

    /**
     * @param string $featureName
     */
    public function setFeatureName(string $featureName)
    {
        $this->featureName = $featureName;
        return $this;
    }

    /**
     * @return bool
     */
    public function isFilter()
    {
        return $this->isFilter;
    }

    /**
     * @param bool $isFilter
     */
    public function setIsFilter($isFilter)
    {
        $this->isFilter = $isFilter;
        return $this;
    }

    /**
     * @return bool
     */
    public function isInfo(): bool
    {
        return $this->isInfo;
    }

    /**
     * @param bool $isInfo
     */
    public function setIsInfo($isInfo)
    {
        $this->isInfo = $isInfo;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDisabled()
    {
        return $this->isDisabled;
    }

    /**
     * @param bool $isDisabled
     */
    public function setIsDisabled($isDisabled)
    {
        $this->isDisabled = $isDisabled;
        return $this;
    }

    /**
     * @return int
     */
    public function getSortFilter()
    {
        return $this->sortFilter;
    }

    /**
     * @param int $sortFilter
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
        return $this->sortInfo;
    }

    /**
     * @param int $sortInfo
     */
    public function setSortInfo($sortInfo)
    {
        $this->sortInfo = $sortInfo;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateInsert()
    {
        return $this->dateInsert;
    }

    /**
     * @param \DateTime $dateInsert
     */
    public function setDateInsert($dateInsert)
    {
        $this->dateInsert = $dateInsert;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateModify()
    {
        return $this->dateModify;
    }

    /**
     * @param \DateTime $dateModify
     */
    public function setDateModify($dateModify)
    {
        $this->dateModify = $dateModify;
        return $this;
    }

}