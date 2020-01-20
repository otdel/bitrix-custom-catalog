<?php

namespace Oip\ProductFeature;

use Oip\GuestUser\Repository\RepositoryInterface;

class DataWrapper
{
    /** @var \Oip\ProductFeature\Repository\RepositoryInterface */
    private $repository;

    /**
     * DataWrapper constructor.
     * @param RepositoryInterface $repository Реализация интерфейса \Oip\ProductFeature\Repository\RepositoryInterface
     */
    public function __construct($repository)
    {
        $this->repository = $repository;
    }

    /**
     * Получение описния характеристик товаров
     *
     * @return ProductFeatureValue[] | null
     * @throws \Exception
     */
    public function getProductFeatures() {
        // Получаем сырые данные из источника данных
        $rawData = $this->repository->getProductFeatures();

        $productFeatures = array();

        // Пробегаемся по всем полученным данным и собираем их них ProductFeature массив
        foreach ($rawData as $queryResult) {
            $productFeature = new ProductFeature([]);
            $productFeature
                ->setId($queryResult["id"])
                ->setCode($queryResult["code"])
                ->setName($queryResult["name"])
                ->setSortFilter($queryResult["sort_filter"])
                ->setSortInfo($queryResult["sort_info"])
                ->setCssFilterClassname($queryResult["css_filter_classname"])
                ->setIsFilter($queryResult["is_filter"])
                ->setIsPredefined($queryResult["is_predefined"])
                ->setIsDisabled($queryResult["is_disabled"])
                ->setDateInsert($queryResult["date_insert"])
                ->setDateModify($queryResult["date_modify"]);
            $productFeatures[$queryResult["code"]] = $productFeature;
        }

        return $productFeatures;
    }

    /**
     * Получение значений характеристик для товаров по их идентификаторам
     *
     * @param int[] $arProductId Массив идентификаторов товаров
     * @return array | null
     * @throws \Exception
     */
    public function getProductFeatureValues($arProductId) {
        // Получаем сырые данные из источника данных
        $rawData = $this->repository->getProductFeatureValues($arProductId);

        $productFeatureValues = array();

        // Пробегаемся по всем полученным данным и собираем их них ProductFeature массив
        foreach ($rawData as $queryResult) {
            $productFeatureValue = new ProductFeatureValue();
            $productFeatureValue
                ->setId($queryResult["id"])
                ->setProductId($queryResult["element_id"])
                ->setFeatureCode($queryResult["feature_code"])
                ->setPredefinedValueId($queryResult["predefined_value_id"])
                ->setValue($queryResult["value"])
                ->setIsDisabled($queryResult["is_disabled"])
                ->setDateInsert($queryResult["date_insert"])
                ->setDateModify($queryResult["date_modify"]);
            $productFeatureValues[] = $productFeatureValue;
        }

        // Собираем список идентификаторов товаров и сразу
        // формируем массив вида [Ключ_код_товара => Все_характеристики_товара]
        $productFeatures = array();
        foreach($productFeatureValues as $productFeatureValue)
        {
            // Созадем элемент массива, если для текущего товара еще нет данных в массиве
            $productId = $productFeatureValue->getProductId();
            if(!in_array($productId, array_keys($productFeatures))) {
                $productFeatures[$productId] = array();
            }
            $productFeatures[$productId][] = $productFeatureValue;
        }

        return $productFeatures;
    }

}
