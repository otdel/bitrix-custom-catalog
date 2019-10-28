<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

require_once(__DIR__."/../Section.php");
require_once(__DIR__."/../UFProperty.php");

use Bitrix\Main\ArgumentException;
use \Bitrix\Main\ArgumentNullException;
use \Bitrix\Main\ArgumentTypeException;
use \Bitrix\Main\LoaderException;
use \Bitrix\Main\SystemException;
use Oip\Custom\Component\Iblock\Section;

/**
 * <?$APPLICATION->IncludeComponent("oip:iblock.section.list","",[
 *   "IBLOCK_ID" => 2,
 *   "BASE_SECTION" => 8,
 *   "DEPTH" => 3,
 *   "SHOW_ELEMENTS_CNT" => false,
 *   "USER_FIELDS" => array("UF_*")
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
        }
        catch (\Bitrix\Main\ArgumentException $e) {
            $this->arResult["EXCEPTION"] = $e->getMessage();
        }

        // ID или код раздела, относительно которого начнется построение дерева
        $this->setDefaultParam($arParams["BASE_SECTION"], 0);
        // Поля для выборки
        $this->setDefaultParam($arParams["SELECT"], array("*"));
        // UF_ поля для выборки
        $this->setDefaultParam($arParams["USER_FIELDS"], array());
        // Поле, по которому производится выборка раздела
        $arParams["FILTER_FIELD_NAME"] = is_int($arParams["BASE_SECTION"]) ? "ID": "CODE";
        // Флаг - показывать или скрывать количество элементов в категории
        $this->setDefaultParam($arParams["SHOW_ELEMENTS_CNT"], false);
        // Максимальная глубина вложенности дерева
        $this->setDefaultParam($arParams["DEPTH"], 1);
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

        // Список/слайдер. По умолчанию список
        $viewTypes = ["LIST", "SLIDER"];
        if(!is_set($arParams["VIEW_TYPE"])) {
            $arParams["VIEW_TYPE"] = "LIST";
        }
        else {
            if (in_array($arParams["VIEW_TYPE"], $viewTypes)) {
                throw new ArgumentException("VIEW_TYPE может принимать только следующие значения: " . implode(", ", $viewTypes));
            }
        }

        return $arParams;
    }

    /** @return array */
    protected function consistFilter()
    {
        $filter = [
            "IBLOCK_ID" => $this->arParams["IBLOCK_ID"]
        ];
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

        // Получаем список разделов
        $dbSection = CIBlockSection::GetList(
            Array(),
            $filter,
            true,
            array_merge($this->arParams["SELECT"], $this->arParams["USER_FIELDS"])
        );

        while($arSection = $dbSection->GetNext(true, false)) {
            $sectionId = $arSection['ID'];
            $parentSectionId = (int) $arSection['IBLOCK_SECTION_ID'];

            foreach($arSection as $key => $sectionField){
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
                        }
                        // Если тип поля - файл (единичный) и файл в поле задан
                        else if ($arSection[$key]["MULTIPLE"] == "N" && $arSection[$key]["VALUE"] != 0) {
                            $this->arFieldsWithFileValues[] = &$arSection[$key];
                            $this->arFiles[$arSection[$key]["VALUE"]] = array();
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
                        }
                        // Если поле принимает только одна значение и оно установлено
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
        return array_shift($arSections)["CHILDS"];
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
        // Получаем информацию о файлах
        $dbRes = \CFile::GetList([],["@ID" => implode(',', array_keys($this->arFiles))]);
        // Формируем массив с инфо о файлах (ключ = id файла)
        $this->arFiles = array();
        while($file = $dbRes->GetNext(true, false)) {
            $this->arFiles[$file["ID"]] = $file;
        }
        return $this;
    }

    /**
     * Получение всех значений пользовательских полей с типом "список"
     *
     * @return self
     */
    protected function getListValues() {
        $this->arUFListValues = array();
        $obEnum = new CUserFieldEnum;
        $rsEnum = $obEnum->GetList(array(), array());
        while($arEnum = $rsEnum->GetNext()){
            $this->arUFListValues[$arEnum["ID"]] = $arEnum;
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

}