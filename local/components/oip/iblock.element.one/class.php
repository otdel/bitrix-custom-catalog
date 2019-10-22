<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Oip\Custom\Component\Iblock\Element;

\CBitrixComponent::includeComponentClass("oip:iblock.element.list");

/**
 *
 * <?$APPLICATION->IncludeComponent("oip:iblock.element.one","",[
 * "BASE" => [
        "IBLOCK_ID" => 2,
        "ELEMENT_ID" => 4,
        "PROPERTIES" => [
            "PICS_NEWS",
            "TEST_STRING",
            "TEST_FILE",
            "TEST_LIST",
        ],
     *  "PROPERTIES" => "all" - все свойства
        "RESIZE_FILE_PROPS" => [600,600],
 *
 *      "COUNT" => "",
        "SHOW_INACTIVE" => "Y",
        "FILTER" => "",
        "SORT_1" => "BY_1",
        "SORT_2" => "BY_2",
        "SHOW_META" => "",
        "INCLUDE_IBLOCK_CHAIN" => "",
        "SHOW_SORT" => "",
        "SHOW_404" => "",
        "SHOW_PAGER" => "",
        "SHOW_SIDEBAR" => "",
*   ],
 *
 *  "ELEMENT_VIEW" => [
        "PICTURE" => [
            "TYPE" => "",
            "HEIGHT" => "",
            "POSITION" => ""
        ],
        "BLOCK" => [
            "COLOR" => "",
            "SIZE" => "",
        ],
        "TITLE" => [
            "ALIGN" => "",
            "CSS" => "",
        ],

        "SHOW_CATEGORY_NAME" => "",
        "SHOW_TAG_LIST" => "",
        "SHOW_BRAND" => "",
        "SHOW_REVIEWS_NUMBER" => "",

        "READ_MORE_BUTTON" => [
            "SHOW" => "",
            "TEXT" => "",
            "SHOW_HOVER_EFFECT" => "",
        ],
    ],
])?>
*/


class COipIblockElementOne extends COipIblockElementList {

    public function executeComponent()
    {
        $this->execute();

        if(empty($this->rawData)) {
            $this->arResult["ERRORS"][] = "Ошибка: элемент не найден";
        }
        else {
            $this->arResult["ELEMENT"] = new Element(reset($this->rawData));
        }

        $this->includeComponentTemplate();
    }

    /**
     * @inheritdoc
    */
    protected function initParams($arParams)
    {
        $arParams = parent::initParams($arParams);

        try {
            if(!is_set($arParams["BASE"]["ELEMENT_ID"])) {
                throw new \Bitrix\Main\ArgumentNullException("ELEMENT_ID");
            }

            if(!intval($arParams["BASE"]["ELEMENT_ID"])) {
                throw new \Bitrix\Main\ArgumentTypeException("ELEMENT_ID");
            }
        }
        catch (\Bitrix\Main\ArgumentException $e) {
            $this->arResult["EXCEPTION"] = $e->getMessage();
        }

        return $arParams;
    }

    /**
     * @inheritdoc
     */
    protected function consistFilter() {
        $filter = parent::consistFilter();

        $filter["ID"] = $this->arParams["BASE"]["ELEMENT_ID"];

        if($this->arParams["BASE"]["SECTION_ID"]) {
           unset($filter["SECTION_ID"]);
        }

        return $filter;
    }
}