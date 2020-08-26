<?php

namespace Oip\ProductFeature\Repository;

use Oip\ProductFeature\ProductFeature;
use Oip\ProductFeature\ProductFeatureValue;
use Oip\ProductFeature\SectionFeatureOption;

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

    /**
     * Получение настроек характеристик внутри раздела
     *
     * @param int[] $arSectionId Массив с идентификаторами разделов
     * @return SectionFeatureOption[] | null
     */
    public function getSectionFeatureOptions($arSectionId);

    /**
     * Получение уникальных значений для характеристики по ее коду
     *
     * @param string $featureCode Код характеристики
     * @param int[] $arSectionIds Коды разделов, из которых следует учитывать товары
     * @return array | null
     * @throws \Exception
     */
    public function getFeatureDistinctValues($featureCode, $arSectionIds);

    /**
     * Получение списка элементов, удовлетворяющих набору фильтров
     *
     * @param array $filters Код характеристики
     * @param int $limit Ограничение количества
     * @param $offset Смещение
     * @return array | null
     */
    public function getFilteredElements($filters, $limit, $offset);
}