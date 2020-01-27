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
use Oip\ProductFeature\Repository\RepositoryInterface;
use Oip\ProductFeature\SectionFeatureOption;

\CBitrixComponent::includeComponentClass("oip:component");

class CProductFeaturesFilter extends \COipComponent
{
    /** @var CacheInfo $cacheInfo Информация о кеше внутри компонента */
    private $cacheInfo;
    /** @var ProductFeature[] $productFeatures */
    private $productFeatures;
    /** @var RepositoryInterface $repository Источник данных */
    private $repository;
    /** @var DataWrapper $dataWrapper Обертка над источником данных */
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

    protected function execute()
    {
        global $DB;
        // Создаем объект - источник данных
        $this->repository = new DBRepository($DB, null);
        // Создаем объект-обертку для операций над источником данных
        $this->dataWrapper = new DataWrapper($this->repository);
        // Если набор данных для фильтрации был передан
        if (isset($_POST["filter"]["action"]) && ($_POST["filter"]["action"] == "doFilter")) {
            $this->arResult["FILTERED_ELEMENTS"] = $this->filterElements();
        }
        // Если не было передано действия - следует показать форму
        else {
            $this->prepareFormData();
        }
    }

    protected function filterElements() {
        $limit = isset($_POST["filter"]["limit"]) ? $_POST["filter"]["limit"] : 1000;
        $offset = isset($_POST["filter"]["offset"]) ? $_POST["filter"]["offset"] : 0;
        return $this->dataWrapper->getFilteredElements($_POST["filter"]["filters"], $limit, $offset);
    }

    protected function prepareFormData() {
        // Получаем навигационную цепочку до текущего раздела
        $navChain = CIBlockSection::GetNavChain($this->arParams["IBLOCK_ID"], $this->arParams["SECTION_ID"]);
        $sectionsChain = array();
        while ($section = $navChain->Fetch()) {
            $sectionsChain[] = $section["ID"];
        }

        // Формируем список характеристик, доступных для включения в фильтр
        // Сначала смотрим на настройки текущей категории, далее поднимаемся по родительским разделам пока не дойдем до самого верха.
        $sectionsChain = array_reverse($sectionsChain);
        // Запрашиваем настройки зарактеристик для текущего раздела и всех его родительских разделов
        $allSectionFeatureOptions = $this->dataWrapper->getSectionFeatureOptions($sectionsChain);

        // Массив, который дудет содержать все настройки характеристик для данного раздела
        /** @var SectionFeatureOption[] $sectionFeatureOptions */
        $sectionFeatureOptions = array();
        foreach ($sectionsChain as $section) {
            // Фильтруем все настройки, оставляя только те, которые относятся к данному разделу
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

        // Сортируем характеристики по их весу sortFilter
        uasort($sectionFeatureOptions, function ($a, $b) {
            return $a->getSortFilter() < $b->getSortFilter() ? 1 : -1;
        });

        // Фильтруем характеристики, оставляя только те, у которых isFilter = true
        $filterSectionFeatures = array_filter($sectionFeatureOptions, function ($sectionFeatureOption) {
            /** @var SectionFeatureOption $sectionFeatureOption */
            return $sectionFeatureOption->isFilter();
        });

        // Получив отфильтрованный список, запрашиваем все уникальные значения для каждой характеристики
        $filterSectionFeatureValues = array();
        foreach ($filterSectionFeatures as $filterSectionFeature) {
            // Для каждой характеристики получаем distinct значения
            $distinctValues = $this->dataWrapper->getFeatureDistinctValues($filterSectionFeature->getFeatureCode());
            $filterSectionFeatureValues[$filterSectionFeature->getFeatureCode()] = array();
            foreach ($distinctValues as $distinctValue) {
                $filterSectionFeatureValues[$filterSectionFeature->getFeatureCode()][] = $distinctValue;
            }
        }

        // Для каждой характеристики сортируем значения
        foreach ($filterSectionFeatureValues as &$filterSectionFeatureValue) {
            uasort($filterSectionFeatureValue, function ($a, $b) use ($filterSectionFeatureValue) {
                if ($filterSectionFeatureValue === array_filter($filterSectionFeatureValue, 'is_numeric')) {
                    return $a - $b > 0 ? 1 : -1;
                } else {
                    return strcmp($a, $b);
                }
            });
        }

        $this->arResult["filterSectionFeatureValues"] = $filterSectionFeatureValues;
        $this->arResult["sectionFeatureOptions"] = $sectionFeatureOptions;
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

        // Код раздела, для которого выводится форма фильтров
        $this->setDefaultParam($arParams["SECTION_ID"], $arParams["IBLOCK_ID"]);

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