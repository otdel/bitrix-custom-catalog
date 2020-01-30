<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\ArgumentException;
use \Bitrix\Main\ArgumentNullException;
use \Bitrix\Main\ArgumentTypeException;
use \Bitrix\Main\LoaderException;
use Bitrix\Main\Service\GeoIp\Data;
use \Bitrix\Main\SystemException;
use Oip\CacheInfo;
use Oip\GuestUser\Repository\RepositoryInterface;
use Oip\ProductFeature\DataWrapper;
use Oip\ProductFeature\ProductFeature;
use Oip\ProductFeature\ProductFeatureValue;
use Oip\ProductFeature\Repository\DBRepository;
use Oip\ProductFeature\SectionFeatureOption;

\CBitrixComponent::includeComponentClass("oip:component");

class CProductFeatures extends \COipComponent
{
    /** @var CacheInfo $cacheInfo Информация о кеше внутри компонента */
    private $cacheInfo;
    /** @var ProductFeature[] $productFeatures */
    private $productFeatures;
    /** @var RepositoryInterface $repository Реализация репозитория для работы с данными */
    private $repository;
    /** @var DataWrapper $dataWrapper Обертка, которая работает с репозиторием */
    private $dataWrapper;

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
        $this->repository = new DBRepository($DB, $this->cacheInfo);
        // Создаем объект-обертку для операций над источником данных
        $this->dataWrapper = new DataWrapper($this->repository);

        $result = array();

        // Запрашиваем перечень характеристик
        $this->productFeatures = $this->dataWrapper->getProductFeatures();

        // Запрашиваем кастомные характеристики
        $customFeatures = $this->dataWrapper->getProductFeatureValues($this->arParams["ELEMENT_ID"]);

        // Для каждого товара устанавливаем кастомные характеристики в виде отдельного подмассива
        foreach ($customFeatures as $productId => &$productsFeatures) {

            $sectionFeatureOptions = $this->getProductSectionFeatures($productId);
            /** @var ProductFeatureValue $productsFeature */
            foreach ($productsFeatures as $feature) {
                if (isset($sectionFeatureOptions[$feature->getFeatureCode()])) {
                    $feature->setSortInfo($sectionFeatureOptions[$feature->getFeatureCode()]->getSortInfo());
                }
            }

            $sortedProductsFeatures = $productsFeatures;
            usort($sortedProductsFeatures, function($a, $b) { return $a->getSortInfo() < $b->getSortInfo() ? 1 : -1; });
            $result[$productId]["productFeatures"] = $sortedProductsFeatures;
        }

        // Отдаем результирующий набор данных
        $this->arResult["productsInfo"] = $result;
        $this->arResult["productFeatures"] = $this->productFeatures;
    }

    /**
     * Получение настроек характеристик внутри раздела, в котором находится товар
     */
    private function getProductSectionFeatures($elementId) {
        // Получаем навигационную цепочку до раздела, в котором находится товар
        $element = CIBlockElement::GetByID($elementId);
        if ($element = $element->Fetch()) {
            $navChain = CIBlockSection::GetNavChain($element["IBLOCK_ID"], $element["IBLOCK_SECTION_ID"]);
            $sectionsChain = array();
            // Добавляем корневой раздел - сам инфоблок
            $sectionsChain[] = $element["IBLOCK_ID"];
            while ($section = $navChain->Fetch()) {
                $sectionsChain[] = $section["ID"];
            }
            // Формируем список характеристик, доступных для включения в фильтр
            // Сначала смотрим на настройки текущей категории, далее поднимаемся по родительским разделам пока не дойдем до самого верха.
            $sectionsChain = array_reverse($sectionsChain);
            // Запрашиваем настройки зарактеристик для текущего раздела и всех его родительских разделов
            $allSectionFeatureOptions = $this->dataWrapper->getSectionFeatureOptions($sectionsChain);

            // Массив, который дудет содержать все настройки характеристик для раздела, в котором находится товар
            /** @var SectionFeatureOption[] $sectionFeatureOptions */
            $sectionFeatureOptions = array();
            foreach ($sectionsChain as $section) {
                // Фильтруем все настройки, оставляя только те, которые относятся к разделу, в котором находится товар
                /** @var SectionFeatureOption[] $currentSectionFeatureOptions */
                $currentSectionFeatureOptions = array_filter($allSectionFeatureOptions, function ($sectionFeatureOptions) use ($section) {
                    /** @var SectionFeatureOption $sectionFeatureOptions */
                    return $sectionFeatureOptions->getSectionId() == $section;
                });

                // Пробегаемся по каждой настройке характеристики, если такой еще нет - добавляем
                foreach ($currentSectionFeatureOptions as $currentSectionFeatureOption) {
                    $isFound = false;
                    foreach ($sectionFeatureOptions as $sectionFeatureOption) {
                        if ($sectionFeatureOption->getFeatureCode() == $currentSectionFeatureOption->getFeatureCode()) {
                            $isFound = true;
                            break;
                        }
                    }
                    // Если настроек для текущей характеристики еще нет в общем наборе настроек - запишем
                    if (!$isFound) {
                        $sectionFeatureOptions[$currentSectionFeatureOption->getFeatureCode()] = $currentSectionFeatureOption;
                    }
                }
            }

            return $sectionFeatureOptions;
        }
        else {
            return null;
        }
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

        try {
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
        );

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