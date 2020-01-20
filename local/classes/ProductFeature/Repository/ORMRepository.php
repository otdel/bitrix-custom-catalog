<?php


namespace Oip\ProductFeature\Repository;

use Bitrix\Main\Application;
use Bitrix\Main\SystemException;
use Oip\IblockElementFeature\ElementFeature\ElementFeatureTable;
use Oip\IblockElementFeature\ElementFeatureValue\ElementFeatureValueTable;
use Oip\IblockElementFeature\Feature\Feature;
use Oip\IblockElementFeature\Feature\FeatureCollection;
use Oip\IblockElementFeature\Feature\FeatureTable;
use Oip\IblockElementFeature\FeatureValue\FeatureValue;
use Oip\IblockElementFeature\FeatureValue\FeatureValueCollection;
use Oip\IblockElementFeature\FeatureValue\FeatureValueTable;
use Oip\ProductFeature\ProductFeature;
use Oip\ProductFeature\ProductFeatureValue;
use Protobuf\Exception;

class ORMRepository implements RepositoryInterface
{

    public function __construct()
    {

    }

    /**
     * @inheritDoc
     */
    public function getProductFeatures($arFeatureId = null) {
        if (is_array($arFeatureId) && !($arFeatureId === array_filter($arFeatureId,'is_int'))) {
            throw new \Exception("Входные данные имеют неверный формат.");
        }
        try {
            // Если массив id характеристик не был передан - запрашиваем все
            $arFilter = isset($arFeatureId) ? array("@id" => $arFeatureId) : array();
            $features = FeatureTable::getList(['filter' => $arFilter]);
            $features = $features->fetchCollection();
            $features->fill();
            // Получаем массив ProductFeature[] из ORM коллекции FeatureCollection
            return $this->FeatureCollectionToProductFeatureArray($features);
        }
        catch (Exception $ex) {
            throw new \Exception("Не удалось получить список характеристик");
        }
    }

    /**
     * Получение значений характеристик для товаров по их идентификаторам
     *
     * @param int[] $arProductId Массив идентификаторов товаров
     * @return ProductFeatureValue[] | null
     * @throws \Exception
     */
    public function getProductFeatureValues($arProductId = null) {
        if (is_array($arProductId) && !($arProductId === array_filter($arProductId,'is_int'))) {
            throw new \Exception("Входные данные имеют неверный формат.");
        }
        try {
            $featureValues = FeatureValueTable::getList();
            $featureValues = $featureValues->fetchCollection();
            $featureValues->fill();
            // Получаем массив ProductFeature[] из ORM коллекции FeatureCollection
            return $this->FeatureValueCollectionToProductFeatureArray($featureValues);
        }
        catch (Exception $ex) {
            throw new \Exception("Не удалось получить список характеристик");
        }
    }

    /**
     * Получение объекта ProductFeature из ORM-овского Feature
     *
     * @param Feature $elementFeature
     * @return ProductFeature
     */
    private function FeatureToProductFeature($elementFeature) {
        $productFeature = new ProductFeature();
        $productFeature->setId($elementFeature->getId());
        $productFeature->setName($elementFeature->getName());
        $productFeature->setSortFilter($elementFeature->getSortFilter());
        $productFeature->setSortInfo($elementFeature->getSortInfo());
        $productFeature->setCssFilterClassname($elementFeature->getCssFilterClassname());
        $productFeature->setIsFilter($elementFeature->getIsFilter());
        $productFeature->setIsPredefined($elementFeature->getIsPredefined());
        $productFeature->setIsDisabled($elementFeature->getIsDisabled());
        $productFeature->setDateInsert($elementFeature->getDateInsert());
        $productFeature->setDateModify($elementFeature->getDateModify());
        return $productFeature;
    }

    /**
     * Получение массива ProductFeature[] из ORM-овской FeatureCollection
     *
     * @param FeatureCollection $featureCollection
     * @return array
     */
    private function FeatureCollectionToProductFeatureArray($featureCollection) {
        $resultFeatures = array();
        foreach ($featureCollection as $feature) {
            $resultFeatures[] = $this->FeatureToProductFeature($feature);
        }
        return $resultFeatures;
    }

    /**
     * Получение объекта ProductFeature из ORM-овского Feature
     *
     * @param FeatureValue $elementFeatureValue
     * @return ProductFeatureValue
     */
    private function FeatureValueToProductFeature($elementFeatureValue) {
        $productFeatureValue = new ProductFeatureValue();
        $productFeatureValue->setId($elementFeatureValue->getId());
        $productFeatureValue->setProductId($elementFeatureValue->getElementId());
        $productFeatureValue->setValue($elementFeatureValue->getValue());
        $productFeatureValue->setPredefinedValueId($elementFeatureValue->getPredefinedValueId());
        $productFeatureValue->setIsDisabled($elementFeatureValue->getIsDisabled());
        $productFeatureValue->setDateInsert($elementFeatureValue->getDateInsert());
        $productFeatureValue->setDateModify($elementFeatureValue->getDateModify());
        return $productFeatureValue;
    }

    /**
     * Получение массива ProductFeature[] из ORM-овской FeatureCollection
     *
     * @param FeatureValueCollection $featureValueCollection
     * @return array
     */
    private function FeatureValueCollectionToProductFeatureArray($featureValueCollection) {
        $resultFeatureValues = array();
        foreach ($featureValueCollection as $featureValue) {
            $resultFeatureValues[] = $this->FeatureValueToProductFeature($featureValue);
        }
        return $resultFeatureValues;
    }

}