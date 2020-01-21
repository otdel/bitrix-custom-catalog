<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\ArgumentException;
use \Bitrix\Main\ArgumentNullException;
use \Bitrix\Main\ArgumentTypeException;
use \Bitrix\Main\LoaderException;
use \Bitrix\Main\SystemException;
use Oip\CacheInfo;
use Oip\ProductFeature\DataWrapper;
use Oip\ProductFeature\ProductFeature;
use Oip\ProductFeature\ProductFeatureValue;
use Oip\ProductFeature\Repository\DBRepository;

\CBitrixComponent::includeComponentClass("oip:component");

class CProductFeatures extends \COipComponent
{
    /** @var CacheInfo $cacheInfo Информация о кеше внутри компонента */
    private $cacheInfo;
    /** @var ProductFeature[] $productFeatures */
    private $productFeatures;

    public function onPrepareComponentParams($arParams)
    {
        return $this->initParams($arParams);
    }

    public function executeComponent()
    {
        if(empty($this->arResult["EXCEPTION"])) {
            try {
                $this->execute();
            } catch (LoaderException $e) {
                $this->arResult["EXCEPTION"] = $e->getMessage();
            }
            catch (SystemException $e) {
                $this->arResult["EXCEPTION"] = $e->getMessage();
            }
        }

        $this->includeComponentTemplate();
    }

    protected function execute() {
        global $DB;
        // Создаем объект - источник данных
        $repository = new DBRepository($DB);
        // Создаем объект-обертку для операций над источником данных
        $dataWrapper = new DataWrapper($repository);

        $result = array();

        // Запрашиваем базовую информацию о товаре (в т.ч. характеристики из UF_ полей)
        $arSelect = array("*", "PROPERTY_ARTICLE", "PROPERTY_BRANDS");
        $arFilter = array("IBLOCK_ID" => $this->arParams["IBLOCK_ID"], "ID" => $this->arParams["ELEMENT_ID"]);
//        $getElements = \CIBlockElement::GetList(array(), $arFilter, false, array(), $arSelect);
//        while ($row = $getElements->Fetch()) {
//            $result[$row["ID"]] = $row;
//        }
        // Запрашиваем перечень характеристик
        $this->productFeatures = $dataWrapper->getProductFeatures();

        // Запрашиваем кастомные характеристики
        $customFeatures = $dataWrapper->getProductFeatureValues($this->arParams["ELEMENT_ID"]);

        // Для каждого товара устанавливаем кастомные характеристики в виде отдельного подмассива
        foreach ($customFeatures as $productId => &$productsFeatures) {
            // Отсортируем характеристики согласно настройкам (поле sort_info)
            /** @var ProductFeatureValue $productFeature */
            foreach ($productsFeatures as &$productFeature) {
                $productFeature->sortInfo = $this->productFeatures[$productFeature->getFeatureCode()]->getSortInfo();
            }
            $sortedProductsFeatures = $productsFeatures;
            usort($sortedProductsFeatures, function($a, $b) { return $a->sortInfo < $b->sortInfo ? 1 : -1; });
            $result[$productId]["productFeatures"] = $sortedProductsFeatures;
        }

        // Отдаем результирующий набор данных
        $this->arResult["productsInfo"] = $result;
        $this->arResult["productFeatures"] = $this->productFeatures;
    }

    /**
     * @param array $arParams
     * @throws ArgumentTypeException | ArgumentException
     * @return array
     */
    protected function initParams($arParams) {
        // Проверка IBLOCK_ID
        if(!isset($arParams["IBLOCK_ID"]) || !intval($arParams["IBLOCK_ID"])) {
            throw new ArgumentTypeException("IBLOCK_ID");
        }
        // Если в качестве ELEMENT_ID был передан не массив, а просто идентификатор - создаем массив из 1 элемента
        if (is_int($arParams["ELEMENT_ID"])) {
            $arParams["ELEMENT_ID"] = array($arParams["ELEMENT_ID"]);
        }
        // Проверка заполненности необходимого параметра - идентификатора (или списка) товара
        else if(!(isset($arParams["ELEMENT_ID"]) || !intval($arParams["ELEMENT_ID"]) || !is_array($arParams["ELEMENT_ID"]))) {
            throw new ArgumentTypeException("ELEMENT_ID");
        }

        // TODO: Запилить кеширование, чтобы не дергать бд каждый раз если решим вызывать компонент отдельно для каждого товара
        /*try {
            // Проверка на валидность параметра "CACHE_TIME"
            if (is_set($arParams["CACHE_TIME"]) && !intval($arParams["CACHE_TIME"])) {
                throw new \Bitrix\Main\ArgumentTypeException("CACHE_TIME");
            }
            // Время жизни кеша
            $this->setDefaultParam($arParams["CACHE_TIME"], 60);
        }
        catch (\Bitrix\Main\ArgumentException $e) {
            $this->arResult["EXCEPTION"] = $e->getMessage();
        }

        // Кешировать выборки из БД. По умолчанию - "N"
        $this->setDefaultParam($arParams["CACHE"], "N");

        // Заполняем информацию о кешировании внутри компонента
        $this->cacheInfo = new CacheInfo(
            $arParams["CACHE"] == "Y",
            $arParams["CACHE_TIME"],
            $this->getCacheId()
        );*/

        return $arParams;
    }


    /**
     * @param mixed $param
     * @param mixed $defaultValue
     */
//    protected function setDefaultParam(&$param, $defaultValue) {
//        if(!is_set($param)) {
//            $param = $defaultValue;
//        }
//    }

    /**
     * @param mixed $param
     * @param boolean $defaultValue
     */
    protected function setDefaultBooleanParam(&$param, $defaultValue) {
        if(!is_set($param) || !is_bool($param)) {
            $param = $defaultValue;
        }
    }

    /**
     *
     * @param string $paramCode
     * @return mixed
     */
//    public function getParam($paramCode) {
//        return $this->getParamRecursive($paramCode, $this->arParams);
//    }

    /**
     * @param string $paramCode
     * @param array $arParams
     * @return mixed
     */
//    protected function getParamRecursive($paramCode, $arParams) {
//        $param = null;
//        foreach ($arParams as $paramName => $paramValue) {
//            if($paramName === $paramCode) {
//                $param = $paramValue;
//                break;
//            }
//            elseif(is_array($paramValue)) {
//                $param = $this->getParamRecursive($paramCode, $paramValue);
//
//                if($param) break;
//            }
//        }
//        return $param;
//    }

}