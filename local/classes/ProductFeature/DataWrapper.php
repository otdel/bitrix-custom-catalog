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
     * Получение описания характеристик товаров
     *
     * @return ProductFeature[] | null
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
                ->setIsFilter($queryResult["is_filter"] == 1)
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
                ->setDateModify($queryResult["date_modify"])
                ->setSortInfo($queryResult["sort_info"]);
            $productFeatureValues[] = $productFeatureValue;
        }

        // Собираем список идентификаторов товаров и сразу
        // формируем массив вида [Ключ_код_товара => Все_характеристики_товара]
        $productFeatures = array();
        foreach($productFeatureValues as $productFeatureValue)
        {
            // Создаем элемент массива, если для текущего товара еще нет данных в массиве
            $productId = $productFeatureValue->getProductId();
            if(!in_array($productId, array_keys($productFeatures))) {
                $productFeatures[$productId] = array();
            }
            $productFeatures[$productId][] = $productFeatureValue;
        }

        return $productFeatures;
    }

    /**
     * Получение настроек характеристик внутри категорий
     *
     * @param int[] $arSectionId Массив идентификаторов товаров
     * @return array | null
     * @throws \Exception
     */
    public function getSectionFeatureOptions($arSectionId) {
        // Получаем сырые данные из источника данных
        $rawData = $this->repository->getSectionFeatureOptions($arSectionId);

        $sectionFeatureOptions = array();

        // Пробегаемся по всем полученным данным и собираем их них ProductFeature массив
        foreach ($rawData as $queryResult) {
            $sectionFeatureOption = new SectionFeatureOption([
                "id" => $queryResult["id"],
                "sectionId" => $queryResult["section_id"],
                "featureCode" => $queryResult["feature_code"],
                "featureName" => $queryResult["feature_name"],
                "isFilter" => $queryResult["is_filter"],
                "isInfo" => $queryResult["is_info"],
                "isDisabled" => $queryResult["is_disabled"],
                "sortFilter" => $queryResult["sort_filter"],
                "sortInfo" => $queryResult["sort_info"],
                "dateInsert" => $queryResult["date_insert"],
                "dateModify" => $queryResult["date_modify"]
            ]);
            $sectionFeatureOptions[] = $sectionFeatureOption;
        }

        return $sectionFeatureOptions;
    }

    /**
     * Получение уникальных значений для характеристики по ее коду
     *
     * @param string $featureCode Код характеристики
     * @param int[]|null $arSectionIds Массив идентификторов разделов, внутри которых смотреть элементы и их значенияё
     * @return array | null
     * @throws \Exception
     */
    public function getFeatureDistinctValues($featureCode, $arSectionIds = null) {
        return $this->repository->getFeatureDistinctValues($featureCode, $arSectionIds);
    }

    /**
     * Получение списка элементов, удовлетворяющих набору фильтров
     *
     * @param array $filters Код характеристики
     * @param int $limit Ограничение количества
     * @param int $offset Смещение
     * @return array | null
     * @throws \Exception
     */
    public function getFilteredElements($filters, $limit = 1000, $offset = 0) {
        return $this->repository->getFilteredElements($filters, $limit, 0);
    }

}
