<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Oip\Custom\Component\Iblock\Element;

use Oip\ProductFeature\Repository;
use Oip\ProductFeature\DataWrapper;
use Oip\CacheInfo;
use Oip\ProductFeature\Repository\DBRepository;

\CBitrixComponent::includeComponentClass("oip:iblock.element.one");

class COipIblockElementOneMR3 extends COipIblockElementOne {

    /** @var CacheInfo $cacheInfo Информация о кешировании внутри компонента */
    protected $cacheInfo;
    /** @var array $properties Список пользовательских свойств, которые необходимо запрашивать */
    protected static $properties = ["ID", "PROPERTY_WARE_ID", "PROPERTY_ARTICLE", "PROPERTY_GUARANTEE", "PROPERTY_COLOR"];
    /** @var Oip\ProductFeature\Repository\ $repository  */
    protected $repository;
    /** @var DataWrapper $dataWrapper */
    protected $dataWrapper;

    public function executeComponent()
    {

        global $DB;
        $this->repository = new DBRepository($DB, $this->cacheInfo);
        $this->dataWrapper = new DataWrapper($this->repository);

        $this->execute();

        if(empty($this->rawData)) {
            $this->arResult["ERRORS"][] = "Ошибка: элемент не найден";
        }
        else {
            $this->arResult["ELEMENT"] = new Element(reset($this->rawData));
        }
        
        $wareProp = $this->arResult["ELEMENT"]->getProp("WARE_ID");
        if (!isset($wareProp)) {
            throw new \Exception("У элемента не найдено пользовательское свойство WARE_ID");
        }
        
        $wareId = $this->arResult["ELEMENT"]->getProp("WARE_ID")->getValue();

        $sectionsChain = $this->getSectionsChain($this->arResult["ELEMENT"]->getIblockId(), $this->arResult["ELEMENT"]->getSectionId());
        $sectionsChain[] = $this->arResult["ELEMENT"]->getIblockId();

        // Получаем список характеристик для категории
        $sectionFeatureOptions = $this->getSectionFeatureOptions($sectionsChain);

        // Сортируем характеристики по "весу" (sort_info)
        uasort($sectionFeatureOptions, function($a, $b) { return $a->getSortFilter() < $b->getSortFilter() ? 1 : -1; });

        $this->arResult["SECTION_FEATURE_OPTIONS"] = $sectionFeatureOptions;

        // Получение всех товаров с характеристиками
        $elements = $this->getWareArticles($wareId);

        $this->includeComponentTemplate();

        $this->addElementView($this->arResult["ELEMENT"]->getId());

        return ($this->arResult["ELEMENT"]) ? $this->arResult["ELEMENT"]->getId() : null;
    }

    protected function initParams($arParams)
    {
        $arParams = parent::initParams($arParams);
        $this->setDefaultBooleanParam($arParams["IS_CACHE"]);
        $this->setDefaultParam($arParams["CACHE_TIME"], 3600);
        $this->cacheInfo = new CacheInfo($arParams["IS_CACHE"], $arParams["CACHE_TIME"], $this->getCacheID());
        return $arParams;
    }

    /**
     * Получение всех артикулов (товаров с различными комбинациями характеристик)
     *
     * @param int $wareId Идентификатор товара из MR3 (пользовательское свойство элемента)
     * @return array
     * @throws Exception
     */
    protected function getWareArticles($wareId) {
        // Получим список id всех элементов, у которых такой же ware_id
        $res = CIBlockElement::GetList([], ["PROPERTY_WARE_ID" => $wareId], false, false, $this->properties);
        $elements = array();
        while ($element = $res->Fetch()) {
            $elements[$element["ID"]] = $element;
        }

        // Запрашиваем характеристики для всех товаров
        $productFeatureValues = $this->dataWrapper->getProductFeatureValues(array_keys($elements));

        // Для каждого товара (уже имеется массив с ключами = id элемента) допишем значения кастомных характеристик
        foreach ($elements as $key => &$element) {
            $element["FEATURES"] = $productFeatureValues[$element["ID"]];
        }

        return $elements;
    }

    protected function getDistinctFeatures($elementId) {
        // Получим список значений для каждой характеристики
        global $DB;
        $repository = new DBRepository($DB, $this->cacheInfo);
        $distinctFeatures = $repository->getDistinctFeatures($elementId);
        return $distinctFeatures;
    }

    /**
     * Получение списка характеристик в категории
     *
     * @param $sectionId
     * @return array|null
     * @throws Exception
     */
    protected function getSectionFeatureOptions($sectionId) {
        // Получим список значений для каждой характеристики
        $distinctFeatures = $this->dataWrapper->getSectionFeatureOptions($sectionId);
        return $distinctFeatures;
    }

    private function getSectionsChain($iblockId, $sectionId)
    {
        // Получаем навигационную цепочку до текущего раздела
        $navChain = CIBlockSection::GetNavChain($iblockId, $sectionId);
        $sectionsChain = array();
        while ($section = $navChain->Fetch()) {
            $sectionsChain[] = $section["ID"];
        }
        // Формируем список характеристик, доступных для включения в фильтр
        // Сначала смотрим на настройки текущей категории, далее поднимаемся по родительским разделам пока не дойдем до самого верха.
        $sectionsChain = array_reverse($sectionsChain);
        return $sectionsChain;
    }

    /**
     * @inheritdoc
    */
//    protected function initParams($arParams)
//    {
//        $arParams = parent::initParams($arParams);
//        $this->setDefaultParam($arParams["ELEMENT_CODE"],"");
//
//        try {
//            if(!$arParams["ELEMENT_CODE"] && !is_set($arParams["ELEMENT_ID"])) {
//                throw new \Bitrix\Main\ArgumentNullException("ELEMENT_ID");
//            }
//
//            if(!$arParams["ELEMENT_CODE"] && !intval($arParams["ELEMENT_ID"])) {
//                throw new \Bitrix\Main\ArgumentTypeException("ELEMENT_ID");
//            }
//        }
//        catch (\Bitrix\Main\ArgumentException $e) {
//            $this->arResult["EXCEPTION"] = $e->getMessage();
//        }
//
//        return $arParams;
//    }

    /**
     * @inheritdoc
     */
//    protected function consistFilter() {
//        $filter = parent::consistFilter();
//
//        if($this->getParam("ELEMENT_CODE")) {
//           $filter["CODE"] = $this->getParam("ELEMENT_CODE");
//        }
//        else {
//            $filter["ID"] = $this->getParam("ELEMENT_ID");
//        }
//
//        if($this->arParams["SECTION_ID"]) {
//           unset($filter["SECTION_ID"]);
//        }
//
//        return $filter;
//    }
}