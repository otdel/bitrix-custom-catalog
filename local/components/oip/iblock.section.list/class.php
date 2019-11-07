<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

require_once(__DIR__."/../Section.php");
require_once(__DIR__."/../UFProperty.php");

use Bitrix\Main\ArgumentException;
use \Bitrix\Main\ArgumentNullException;
use \Bitrix\Main\ArgumentTypeException;
use Bitrix\Main\Data\Cache;
use \Bitrix\Main\LoaderException;
use \Bitrix\Main\SystemException;
use Oip\Custom\Component\Iblock\Section;

/**
 * <?$APPLICATION->IncludeComponent("oip:iblock.section.list","",[
 *   "IBLOCK_ID" => 2,
 *   "BASE_SECTION" => 8,
 *   "DEPTH" => 3,
 *   "SHOW_ELEMENTS_CNT" => false,
 *   "USER_FIELDS" => array("UF_*"),
 *   "CACHE" => "Y"
 *   ])?>
 */
class COipIblockSectionList extends \CBitrixComponent
{
    /** @var array $arSectionsRaw "Сырой" массив с разделами */
    private $arSectionsRaw = array();
    /** @var Section[] $arSections Массив с разделами */
    private $arSections = array();
    /** @var array $arUserFields Массив типов пользовательских полей */
    private $arUserFields;
    /** @var array $arUFListValues Массив значений пользовательских свойств для полей типа "Список" */
    private $arUFListValues;
    /** @var array $arFileFields Массив с идентификаторами файлов */
    private $arFiles = array();
    /** @var array $arFileFields Массив с ссылками на UF_ поля типа "Список" */
    private $arFieldsWithEnumerationValues = array();
    /** @var array $arFileFields Массив с ссылками на UF_ поля типа "Файл" */
    private $arFieldsWithFileValues = array();
    /** @var string $cacheKey Главная часть ключа кеша (название компонента + md5 массива arParams) */
    private $cacheKey;
    /** @var int $cacheLifeTime Время жизни кеша */
    private $cacheLifeTime = 300;
    /** @var boolean $isCacheActual Флаг - включено ли кеширование */
    private $isCacheEnabled;
    /** @var boolean $isCacheActual Флаг - актуальный кеш или нет. Нужен для одновременного обновления всех кешей, если один "просрочился" */
    private $isCacheActual;

    public function onPrepareComponentParams($arParams)
    {
        return $this->initParams($arParams);
    }

    public function executeComponent()
    {
        if(empty($this->arResult["EXCEPTION"])) {
            try {
                if (!\Bitrix\Main\Loader::includeModule("iblock")) {
                    throw new \Bitrix\Main\SystemException("Module iblock is not installed");
                }

                $this->execute();
            } catch (LoaderException $e) {
                $this->arResult["EXCEPTION"] = $e->getMessage();
            }
            catch (SystemException $e) {
                $this->arResult["EXCEPTION"] = $e->getMessage();
            }
        }

        $this->includeComponentTemplate();

        // Если компонент вызван для вывода деталки раздела - отдаем массив
        // значений некоторых полей, для использования в других компонентах
        if ($this->isSingleSection()) {
            /** @var Section $section */
            $section = array_shift($this->arResult["SECTIONS"]);
            return array(
                "SECTION_NAME" => $section->getName(),
                "UF_ELEMENTS_NUMBER" => $section->getPropValue("UF_ELEMENTS_NUMBER"),
                "UF_COLUMNS_COUNT" => $section->getPropValue("UF_COLUMNS_COUNT"),
                "UF_SIDEBAR_WIDTH" => $section->getPropValue("UF_SIDEBAR_WIDTH"),
                "UF_ELEMENT_TITLE_CSS" => $section->getPropValue("UF_ELEMENT_TITLE_CSS"),
                "UF_SIDEBAR_LIST" => $section->getPropValue("UF_SIDEBAR_LIST"),
                "UF_SIDEBAR_ELEMENT" => $section->getPropValue("UF_SIDEBAR_ELEMENT"),
                "UF_SAME_ELEMENT" => $section->getPropValue("UF_SAME_ELEMENT"),
                "UF_POPULAR_WITH_THIS" => $section->getPropValue("UF_POPULAR_WITH_THIS")
            );
        }

    }

    protected function execute() {
        // Получение разделов
        $this->arSectionsRaw = $this->getSectionList();

        // Если BASE_SECTION пришел пустым - значит выводить нужно относительно самого верхнего уровня
        if (!isset($this->arParams["BASE_SECTION"]) || ($this->arParams["BASE_SECTION"] == 0)) {
            $sectionArray = $this->arSectionsRaw;
        }
        // Иначе строим относительно выбранного раздела
        else {
            $sectionArray = $this->extractSectionFromArray(
                $this->arSectionsRaw,
                $this->arParams["FILTER_FIELD_NAME"],
                $this->arParams["BASE_SECTION"]
            );
        }

        // Убираем лишние элементы, которые выходят за заданную глубину вложенности
        $this->buildSectionArray($sectionArray, 0, $this->arParams["DEPTH"]);

        // Получение значений для полей типа "список"
        // 1. Получим все значения, которые могут принимать пользовательские поля с типом "список"
        $this->getListValues();
        // 2. Проставляем значения для полей с типом "список"
        $this->updateListValues();

        // Получение значений для полей типа "файл"
        // 1. Запрашиваем информацию по файлам
        $this->getFileValues();
        // 2. Проставляем значения для полей с типом "file"
        $this->updateFileValues();

        // Отдаем дерево разделов в результирующий массив
        $this->arSectionsRaw = $sectionArray;

        // Строим массив Section
        $this->buildSectionObjectsArray();

        // Передаем массив разделов в arResult для вывода в шаблон
        $this->arResult["SECTIONS"] = $this->arSections;
    }

    /**
     * Построение объектов Section из получившегося массива разделов
     */
    protected function buildSectionObjectsArray() {
        foreach ($this->arSectionsRaw as $sectionRaw) {
            $section = new Section($sectionRaw);
            $this->arSections[] = $section;
        }
    }

    /**
     * @param array $arParams
     * @throws ArgumentNullException | ArgumentTypeException | ArgumentException
     * @return array
     */
    protected function initParams($arParams) {
        try {
            // ID инфоблока, внутри которого просматриваются разделы
            if(!is_set($arParams["IBLOCK_ID"])) {
                throw new \Bitrix\Main\ArgumentNullException("IBLOCK_ID");
            }
            if(!intval($arParams["IBLOCK_ID"])) {
                throw new \Bitrix\Main\ArgumentTypeException("IBLOCK_ID");
            }

            // Время жизни кеша
            $this->setDefaultParam($arParams["CACHE_TIME"], 300);
            $this->cacheLifeTime = $arParams["CACHE_TIME"];
        }
        catch (\Bitrix\Main\ArgumentException $e) {
            $this->arResult["EXCEPTION"] = $e->getMessage();
        }

        // Кешировать выборки из БД. По умолчанию - "Y"
        $this->setDefaultParam($arParams["CACHE"], "Y");
        $this->isCacheEnabled = $arParams["CACHE"] =="Y";
        // ID или код раздела, относительно которого начнется построение дерева
        $this->setDefaultParam($arParams["BASE_SECTION"], 0);
        // Массив с критериями фильтра
        $this->setDefaultParam($arParams["FILTER"], array());
        // Массив с критериями сортировки
        $this->setDefaultParam($arParams["ORDER"], array("ID" => SORT_ASC));
        // Поля для выборки
        $this->setDefaultParam($arParams["SELECT"], array("*"));
        // UF_ поля для выборки
        $this->setDefaultParam($arParams["USER_FIELDS"], array());
        // Флаг - показывать или скрывать количество элементов в категории
        $this->setDefaultParam($arParams["SHOW_ELEMENTS_CNT"], false);
        // Максимальная глубина вложенности дерева
        $this->setDefaultParam($arParams["DEPTH"], -1);
        // Текст заголовка. По умолчанию ""
        $this->setDefaultParam($arParams["TITLE_TEXT"], "");
        // Класс заголовка. По умолчанию ""
        $this->setDefaultParam($arParams["TITLE_CLASS"], "");
        // Скрывать/показывать превью. По умолчанию скрывать.
        $this->setDefaultBooleanParam($arParams["SHOW_PREVIEW"], false);
        // Тип списка, строка. По умолчанию .uk-nav.
        $this->setDefaultParam($arParams["LIST_TYPE"], ".uk-nav");
        // Стиль списка, строка. По умолчанию .uk-nav-default
        $this->setDefaultParam($arParams["LIST_CLASS"], ".uk-nav-default");
        // Дополнительный класс списка, строка. По умолчанию ""
        $this->setDefaultParam($arParams["LIST_ADDITIONAL_CLASS"], "");
        // Атрибут списка, строка. По умолчанию ""
        $this->setDefaultParam($arParams["LIST_ATTRIBUTE"], "");
        // Класс(ы) для превью картинки. По умолчанию ""
        $this->setDefaultParam($arParams["PREVIEW_PICTURE_CLASS"], "");
        // Поле, по которому производится выборка раздела
        $arParams["FILTER_FIELD_NAME"] = is_int($arParams["BASE_SECTION"]) ? "ID": "CODE";

        // Список/слайдер. По умолчанию список
        $viewTypes = ["LIST", "SLIDER"];
        if(!is_set($arParams["VIEW_TYPE"])) {
            $arParams["VIEW_TYPE"] = "LIST";
        }
        else {
            if (!in_array($arParams["VIEW_TYPE"], $viewTypes)) {
                throw new ArgumentException("VIEW_TYPE может принимать только следующие значения: " . implode(", ", $viewTypes));
            }
        }

        // Формируем часть ключа кеша, основанную на параметрах
        // Отсортируем массив параметров по ключу в алфавитном порядке
        // (Чтобы при перестановке ключей, но одинаковых значениях, создавался одинаковый ключ)
        // ksort($arParams);
        // Формируем ключ кеша для текущего набора данных (arParams)
        //$this->cacheKey = "section.list." . md5(serialize($arParams));
        $this->cacheKey = $this->getCacheId();

        return $arParams;
    }

    /** @return array */
    protected function consistFilter()
    {
        $filter = array_merge(
            array("IBLOCK_ID" => $this->arParams["IBLOCK_ID"]),
            $this->arParams["FILTER"]
        );
        return $filter;
    }

    /**
     * Построение массива раздела с определенной глубиной вложенности
     *
     * @param array &$sectionsArray Массив с разделом, с которого начинается вывод
     * @param int $currentDepth Текущий уровень вложенности (для рекурсии)
     * @param int $maxDepth Максимальный уровень вложенности
     */
    protected function buildSectionArray(&$sectionsArray, $currentDepth, $maxDepth) {
        foreach ($sectionsArray as &$sectionArray) {
            // Если есть дочерние категории и они не выходят за глубину вложенности
            if ($maxDepth != -1 && $currentDepth + 1 > $maxDepth) {
                unset($sectionArray["CHILDS"]);
            }
            if (isset($sectionArray["CHILDS"])) {
                $this->buildSectionArray($sectionArray["CHILDS"], $currentDepth + 1, $maxDepth);
            }
        }
    }

    /**
     * Формирование массива со всеми категориями (включая дочерние)
     *
     * @return array
     */
    function getSectionList()
    {
        // Запрашиваем информацию о пользовательских полях внутри раздела
        $this->getUserFields();

        // Список полей с файловыми значениями (эти поля нужно будет обновить, получив инфу о файлах)
        $this->arFieldsWithFileValues = array();

        // Формируем фильтр для выборки разделов
        $filter = $this->consistFilter();

        // "Сырые" данные о разделах. Получаются либо из кеша, либо из CIBlockSection::GetList
        $arRawSections = array();

        // Формируем ключ для кеша сырых данных о разделах
        $cacheKey = $this->cacheKey . ".arRawSections";

        // Получаем экземпляр класса
        $cache = Cache::createInstance();

        // Проверяем кеш. TTL задается в секундах
        // Если кеш есть и он включен
        if ($this->isCacheEnabled && $cache->initCache($this->cacheLifeTime, $cacheKey)) {
            // Устанавливаем флаг что кеш актуален
            $this->isCacheActual = true;
            // Достаем переменные из кеша
            $vars = $cache->getVars();
            $arRawSections = unserialize($vars["arRawSections"]);
        }
        // Если кеша нет или он неактуален (или выключен)
        elseif ($cache->startDataCache()) {
            // Устанавливаем флаг что кеш неактуален
            $this->isCacheActual = false;
            // Получаем список разделов
            $dbSection = CIBlockSection::GetList(
                array("LEFT_MARGIN" => "ASC"),
                $filter,
                true,
                array_merge($this->arParams["SELECT"], $this->arParams["USER_FIELDS"])
            );
            // Переносим все данные из GetList в массив сырых данных
            while ($arSection = $dbSection->GetNext(true, false)) {
                $arRawSections[] = $arSection;
            }
            // Записываем в кеш
            $cache->endDataCache(array("arRawSections" => serialize($arRawSections)));
        }

        foreach ($arRawSections as $arSection) {
            $sectionId = $arSection['ID'];
            $parentSectionId = (int)$arSection['IBLOCK_SECTION_ID'];

            // Если установлено изображение
            if ($arSection["PICTURE"] > 0) {
                // Запоминаем id файла (картинки)
                $fileId = $arSection["PICTURE"];
                $arSection["PICTURE"] = array();
                $arSection["PICTURE"]["RAW_VALUE"] = $fileId;
                $arSection["PICTURE"]["VALUE"] = array();
                $this->arFieldsWithFileValues[] = &$arSection["PICTURE"];
                // Добавим изображение в массив файлов с ключом, равным id файла
                $this->arFiles[$fileId] = array();
            }

            // Если установлено детальное изображение
            if ($arSection["DETAIL_PICTURE"] > 0) {
                // Запоминаем id файла (картинки)
                $fileId = $arSection["DETAIL_PICTURE"];
                $arSection["DETAIL_PICTURE"] = array();
                $arSection["DETAIL_PICTURE"]["RAW_VALUE"] = $fileId;
                $arSection["DETAIL_PICTURE"]["VALUE"] = array();
                $this->arFieldsWithFileValues[] = &$arSection["DETAIL_PICTURE"];
                // Добавим изображение в массив файлов с ключом, равным id файла
                $this->arFiles[$fileId] = array();
            }

            foreach ($arSection as $key => $sectionField) {
                if (substr($key, 0, 3) == "UF_") {
                    // Добавляем подмассив с пользовательским полем
                    $arSection[$key] = $this->arUserFields[$key];

                    // RAW_VALUE - "Сырое" значение, хранящее в базе.
                    // Для простых типов (таких как строки и числа) будет совпадать с VALUE
                    $arSection[$key]["RAW_VALUE"] = $sectionField;
                    $arSection[$key]["VALUE"] = $sectionField;

                    // Для поля типа "список" - запоминаем ссылку на данный элемент массива,
                    // чтобы позже получить инфу о перечисляемых значениях и проставить ее данному элементу (разделу)
                    if ($arSection[$key]["USER_TYPE_ID"] == "enumeration" && $arSection[$key]["VALUE"] != 0) {
                        $this->arFieldsWithEnumerationValues[] = &$arSection[$key];
                    }
                    // Для поля типа "файл" - запоминаем ссылку на данный элемент массива,
                    // чтобы позже получить инфу о файле и проставить ее данному элементу (разделу)
                    else if ($arSection[$key]["USER_TYPE_ID"] == "file") {
                        // Если тип поля - файл (множественный) и файлы в поле заданы
                        if ($arSection[$key]["MULTIPLE"] == "Y" && $arSection[$key]["VALUE"]) {
                            // Добавляем поле в список тех, для которых потом нужно проапдейтить инфу о файлах
                            $this->arFieldsWithFileValues[] = &$arSection[$key];
                            // Пробегаемся по каждому файлу
                            foreach ($arSection[$key]["VALUE"] as $file) {
                                $this->arFiles[$file] = array();
                            }
                        } // Если тип поля - файл (единичный) и файл в поле задан
                        else if ($arSection[$key]["MULTIPLE"] == "N" && $arSection[$key]["VALUE"] != 0) {
                            // Сбрасываем старое значение "VALUE", которое являлось строкой с id файла
                            $arSection[$key]["VALUE"] = array();
                            $arSection[$key]["VALUE"][$arSection[$key]["RAW_VALUE"]] = array();
                            $this->arFieldsWithFileValues[] = &$arSection[$key];
                            $this->arFiles[$arSection[$key]["RAW_VALUE"]] = array();
                        }
                    }
                    // Для поля типа "привязка к элементу инфоблока" - если привязан один эелемент,
                    // формируем массив из одного элемента с ключом - айди элемента инфоблока
                    else if ($arSection[$key]["USER_TYPE_ID"] == "iblock_element") {
                        // Сбрасываем старое значение "VALUE", которое являлось строкой
                        $arSection[$key]["VALUE"] = array();
                        if ($arSection[$key]["MULTIPLE"] == "Y") {
                            foreach ($arSection[$key]["RAW_VALUE"] as $value) {
                                $arSection[$key]["VALUE"][$value] = array();
                            }
                        } // Если поле принимает только одна значение и оно установлено
                        else if ($arSection[$key]["MULTIPLE"] == "N" && $arSection[$key]["RAW_VALUE"] != 0) {
                            // Cоздаем единственный элемент в виде пустого массива с ключом - id привязанного элемента инфоблока
                            $arSection[$key]["VALUE"][$arSection[$key]["RAW_VALUE"]] = array();
                        }
                    }
                }
            }

            $arSections[$parentSectionId]['CHILDS'][$sectionId] = $arSection;

            $arSections[$sectionId] = &$arSections[$parentSectionId]['CHILDS'][$sectionId];

        }

        // Сортируем массив по заданным критериям
        $this->sortSectionsArray($arSections[0]["CHILDS"], $this->arParams["ORDER"]);

        return $arSections[0]["CHILDS"];
    }

    /**
     * Сортировка массива разделов. Сортируются только элементы на одной глубине
     *
     * @param array $arSections Массив разделов
     * @param array $arSortConditions Критерии сортировки вида array('CREATED_BY' => SORT_ASC, 'NAME' => SORT_ASC)
     */
    private function sortSectionsArray(&$arSections, $arSortConditions) {
        // Сортируем разделы на текущем уровне
        $arSections = $this->arrayMultiSort($arSections, $arSortConditions);
        // Если есть дочерние разделы - сортируем и их
        foreach ($arSections as &$arSection) {
            if (isset($arSection["CHILDS"])) {
                $this->sortSectionsArray($arSection["CHILDS"], $arSortConditions);
            }
        }
    }

    /**
     * Сортировка многомерного массива по заданным критериям
     * Основа для функции позаимствована с https://www.php.net/manual/ru/function.array-multisort.php
     *
     * @param array $array Исходный массив
     * @param array $cols Критерии сортировки
     * @return array Отсортированный массив
     */
    function arrayMultiSort($array, $cols)
    {
        $colarr = array();
        foreach ($cols as $col => $order) {
            $colarr[$col] = array();
            foreach ($array as $k => $row) {
                // Если это UF_ поле, то значение берем из его поля "VALUE"
                if (substr($col, 0, 3) == "UF_") {
                    $colarr[$col]['_'.$k] = strtolower($row[$col]["VALUE"]);
                }
                else {
                    $colarr[$col]['_'.$k] = strtolower($row[$col]);
                }
            }

        }
        $eval = 'array_multisort(';
        foreach ($cols as $col => $order) {
            $eval .= '$colarr[\''.$col.'\'],'.$order.',';
        }
        $eval = substr($eval,0,-1).');';
        eval($eval);
        $ret = array();
        foreach ($colarr as $col => $arr) {
            foreach ($arr as $k => $v) {
                $k = substr($k,1);
                if (!isset($ret[$k])) $ret[$k] = $array[$k];
                $ret[$k][$col] = $array[$k][$col];
            }
        }
        return $ret;
    }

    /**
     * Проставление значений в полях типа "Файл"
     *
     * @return self
     */
    private function updateFileValues() {
        foreach ($this->arFieldsWithFileValues as &$field) {
            // Обнуляем массив VALUE
            $field["VALUE"] = array();
            // Если это множественное поле
            if ($field["MULTIPLE"] == "Y" && is_array($field["VALUE"])) {
                // Заполняем новый массив VALUE файлами с ключами - id файлов
                foreach ($field["RAW_VALUE"] as $file) {
                    $field["VALUE"][$file] = $this->arFiles[$file];
                }
            }
            else {
                $field["VALUE"][$field["RAW_VALUE"]] = $this->arFiles[$field["RAW_VALUE"]];
            }
        }
        return $this;
    }

    /**
     * Проставление значений в полях типа "Список"
     *
     * @return self
     */
    private function updateListValues() {
        foreach ($this->arFieldsWithEnumerationValues as &$field) {
            // Сбрасываем поле "VALUE"
            $field["VALUE"] = array();

            // Если это поле с единственным значением
            if ($field["MULTIPLE"] == "N") {
                $field["VALUE"][$field["RAW_VALUE"]] = $this->arUFListValues[$field["RAW_VALUE"]];
            }
            // Если это поле с множественным значением
            else if ($field["MULTIPLE"] == "Y" && $field["RAW_VALUE"] !== 0) {
                foreach ($field["RAW_VALUE"] as $value) {
                    $field["VALUE"][$value] = $this->arUFListValues[$value];
                }
                $field["VALUE"][$field["RAW_VALUE"]] = $this->arUFListValues[$field["RAW_VALUE"]];
            }
        }
        return $this;
    }

    /**
     * Получение информации о файлах
     *
     * @return self
     */
    protected function getFileValues() {

        // Формируем ключ кеша
        $cacheKey = $this->cacheKey . ".arFiles";

        // Получаем экземпляр класса кеша
        $cache = Cache::createInstance();
        // Проверяем кеш. TTL задается в секундах
        // Если кеш есть, он включен и он актуальный у основного набора данных (массива разделов)
        if ($this->isCacheEnabled && $cache->initCache($this->cacheLifeTime, $cacheKey) && $this->isCacheActual) {
            // Достаем переменные из кеша
            $vars = $cache->getVars();
            $this->arFiles = unserialize($vars["arFiles"]);
        }
        // Если кеша нет или он неактуален (или выключен)
        elseif ($cache->startDataCache()) {
            // Получаем информацию о файлах
            $dbRes = \CFile::GetList([],["@ID" => implode(',', array_keys($this->arFiles))]);
            // Формируем массив с инфо о файлах (ключ = id файла)
            $this->arFiles = array();
            while($file = $dbRes->GetNext(true, false)) {
                $this->arFiles[$file["ID"]] = $file;
            }
            // Записываем в кеш
            $cache->endDataCache(array("arFiles" => serialize($this->arFiles)));
        }

        return $this;
    }

    /**
     * Получение всех значений пользовательских полей с типом "список"
     *
     * @return self
     */
    protected function getListValues()
    {
        $this->arUFListValues = array();

        // Формируем ключ кеша
        $cacheKey = $this->cacheKey . ".arListValues";

        // Получаем экземпляр класса кеша
        $cache = Cache::createInstance();
        // Проверяем кеш. TTL задается в секундах
        // Если кеш есть и он актуальный у основного набора данных (массива разделов) (и он включен)
        if ($this->isCacheEnabled && $cache->initCache($this->cacheLifeTime, $cacheKey) && $this->isCacheActual) {
            // Достаем переменные из кеша
            $vars = $cache->getVars();
            $this->arUFListValues = unserialize($vars["arUFListValues"]);
        }
        // Если кеша нет или он неактуален (или он выключен)
        elseif ($cache->startDataCache()) {
            $obEnum = new CUserFieldEnum;
            $rsEnum = $obEnum->GetList(array(), array());
            while($arEnum = $rsEnum->GetNext()){
                $this->arUFListValues[$arEnum["ID"]] = $arEnum;
            }
            // Записываем в кеш
            $cache->endDataCache(array("arUFListValues" => serialize($this->arUFListValues)));
        }

        return $this;
    }

    /**
     * Получение типов пользовательских полей
     *
     * @return self
     */
    protected function getUserFields() {
        $this->arUserFields = array();
        $userTypes = CUserTypeEntity::GetList(array(), array("ENTITY_ID" => "IBLOCK_" . $this->arParams["IBLOCK_ID"] . "_SECTION"));
        while ($userType = $userTypes->Fetch()) {
            $this->arUserFields[$userType["FIELD_NAME"]] = $userType;
        }
        return $this;
    }

    /**
     * Извлечение искомого раздела в массиве разделов
     *
     * @param array $sectionsArray
     * @param string $fieldName
     * @param string|int $sectionValue
     * @return array|null
     */
    protected function extractSectionFromArray($sectionsArray, $fieldName, $sectionValue) {
        $foundSection = null;
        foreach ($sectionsArray as $sectionArray) {
            // Если ключевое поле совпадает по искомому значению - мы нашли раздел
            if ($sectionArray[$fieldName] == $sectionValue) {
                return array($sectionArray);
                break;
            }
            // Дошли до сюда - искомый раздел еще не встретился. Пробегаемся по подразделам
            else if (isset($sectionArray["CHILDS"])) {
                $foundSection = $this->extractSectionFromArray($sectionArray["CHILDS"], $fieldName, $sectionValue);
                if (isset($foundSection))
                    return $foundSection;
            }
        }
        return null;
    }

    /**
     * @param mixed $param
     * @param mixed $defaultValue
     */
    protected function setDefaultParam(&$param, $defaultValue) {
        if(!is_set($param)) {
            $param = $defaultValue;
        }
    }

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
    public function getParam($paramCode) {
        return $this->getParamRecursive($paramCode, $this->arParams);
    }

    /**
     * @param string $paramCode
     * @param array $arParams
     * @return mixed
     */
    protected function getParamRecursive($paramCode, $arParams) {
        $param = null;
        foreach ($arParams as $paramName => $paramValue) {
            if($paramName === $paramCode) {
                $param = $paramValue;
                break;
            }
            elseif(is_array($paramValue)) {
                $param = $this->getParamRecursive($paramCode, $paramValue);

                if($param) break;
            }
        }
        return $param;
    }

    /**
     * Флаш - Вызван ли компонент для просмотра одного конкретного раздела (деталки)
     *
     * @return bool
     */
    public function isSingleSection() {
        return $this->getParam("BASE_SECTION") > 0 && $this->getParam("DEPTH") == 0;
    }

}