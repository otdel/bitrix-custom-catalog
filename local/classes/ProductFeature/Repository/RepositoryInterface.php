<?php

namespace Oip\ProductFeature\Repository;

use Oip\ProductFeature\ProductFeature;
use Oip\ProductFeature\ProductFeatureValue;

interface RepositoryInterface
{

    /**
     * Инициализация характеристик
     */
    public function initFeatures();

    /**
     * Получение списка характеристик (Название характеристики, ее код, участвует ли в фильтрации и т.д.)
     *
     * @return ProductFeature[] | null
     */
    public function getProductFeatures();

    /**
     * Получение значений характеристик товара
     *
     * @param int[] $arProductId Массив с идентификаторами товаров
     * @return ProductFeatureValue[] | null
     */
    public function getProductFeatureValues($arProductId);

    /**
     * Установка характеристик для товара
     * @param int $productId Идентификатор товара
     * @param array() $featuresToSet Массив характеристик вида ["ID ХАРАКТЕРИСТИКИ" => "ЗНАЧЕНИЕ"], которые следует установить товару
     * @throws \Exception
     */
    public function setProductFeatureValues($productId, $featuresToUpdate);

    /**
     * Создание новой характеристики
     *
     * @param ProductFeature $productFeature Создаваемая характеристика
     * @return int|string
     */
    public function createProperty(ProductFeature $productFeature);
}