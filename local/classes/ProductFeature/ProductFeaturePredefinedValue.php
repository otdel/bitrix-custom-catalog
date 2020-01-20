<?php

namespace Oip\ProductFeature;

class ProductFeaturePredefinedValue
{
    /** @var int $id Идентификатор записи */
    private $id;
    /** @var int $productId Идентификатор продукта */
    private $productId;
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
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getProductId(): int
    {
        return $this->productId;
    }

    /**
     * @param int $productId
     */
    public function setProductId(int $productId): void
    {
        $this->productId = $productId;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function getPredefinedValueId(): int
    {
        return $this->predefinedValueId;
    }

    /**
     * @param int $predefinedValueId
     */
    public function setPredefinedValueId(int $predefinedValueId): void
    {
        $this->predefinedValueId = $predefinedValueId;
    }

    /**
     * @return int
     */
    public function getIsDisabled(): int
    {
        return $this->isDisabled;
    }

    /**
     * @param int $isDisabled
     */
    public function setIsDisabled(int $isDisabled): void
    {
        $this->isDisabled = $isDisabled;
    }

    /**
     * @return int
     */
    public function getDateInsert(): int
    {
        return $this->dateInsert;
    }

    /**
     * @param int $dateInsert
     */
    public function setDateInsert(int $dateInsert): void
    {
        $this->dateInsert = $dateInsert;
    }

    /**
     * @return int
     */
    public function getDateModify(): int
    {
        return $this->dateModify;
    }

    /**
     * @param int $dateModify
     */
    public function setDateModify(int $dateModify): void
    {
        $this->dateModify = $dateModify;
    }

}