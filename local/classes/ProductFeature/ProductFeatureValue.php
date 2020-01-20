<?php

namespace Oip\ProductFeature;

class ProductFeatureValue
{
    /** @var int $id Идентификатор записи */
    private $id;
    /** @var int $productId Идентификатор продукта */
    private $productId;
    /** @var string $featureCode Код характеристики */
    private $featureCode;
    /** @var string $value Вручную заполненное значение */
    private $value;
    /** @var int $predefinedValueId Идентификатор предопределенного значения */
    private $predefinedValueId;
    /** @var int $is_disabled Признак отключения (мягкого удаления) характеристики */
    private $isDisabled;
    /** @var int $date_insert Дата добавления записи в таблицу (timestamp) */
    private $dateInsert;
    /** @var int $date_modify Дата последнего изменения записи (timestamp) */
    private $dateModify;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return ProductFeatureValue
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * @param int $productId
     * @return ProductFeatureValue
     */
    public function setProductId($productId)
    {
        $this->productId = $productId;
        return $this;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return ProductFeatureValue
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return int
     */
    public function getPredefinedValueId()
    {
        return $this->predefinedValueId;
    }

    /**
     * @param int $predefinedValueId
     * @return ProductFeatureValue
     */
    public function setPredefinedValueId($predefinedValueId)
    {
        $this->predefinedValueId = $predefinedValueId;
        return $this;
    }

    /**
     * @return int
     */
    public function getIsDisabled()
    {
        return $this->isDisabled;
    }

    /**
     * @param int $isDisabled
     * @return ProductFeatureValue
     */
    public function setIsDisabled($isDisabled)
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
     * @return ProductFeatureValue
     */
    public function setDateInsert($dateInsert)
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
     * @return ProductFeatureValue
     */
    public function setDateModify($dateModify)
    {
        $this->dateModify = $dateModify;
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
     * @return ProductFeatureValue
     */
    public function setFeatureCode($featureCode)
    {
        $this->featureCode = $featureCode;
        return $this;
    }

}