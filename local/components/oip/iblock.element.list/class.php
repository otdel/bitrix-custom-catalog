<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

require_once(__DIR__."/../Element.php");
require_once(__DIR__."/../Property.php");

use \Bitrix\Main\ArgumentNullException;
use \Bitrix\Main\ArgumentTypeException;
use \Bitrix\Main\LoaderException;
use \Bitrix\Main\SystemException;

use Oip\Custom\Component\Iblock\Element;

/**
 * это старый пример структуры - массив параметров плоский (кроме properties), вызывать, соединяя детей с родителями через _
 *
 * <?$APPLICATION->IncludeComponent("oip:iblock.element.list","",[
 *  "BASE" => [
        "IBLOCK_ID" => 2,
        "SECTION_ID" => 8,
        "SHOW_INACTIVE" => "Y" - показать и неактивные
        "PROPERTIES" => [
            "PICS_NEWS",
            "TEST_STRING",
            "TEST_FILE",
            "TEST_LIST",
        ],
        "PROPERTIES" => "all" - все свойства
        "RESIZE_FILE_PROPS" => [600,600]
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
 *  ],
 *
 * "LIST_VIEW" => [
        "SHOW_SIDEBAR" => "Y",

        "TITLE" => [
            "TEXT" => "",
            "TAG" => "",
            "CSS" => "",
            "ICON_CSS" => "",
            "ALIGN" => "",
        ],

        "WRAP" => [
            "COLOR" => "",
            "SIZE" => "",
            "ADD_CSS" => "",
        ],
        "CONTAINER" => [
            "WIDTH_CSS" => "",
            "TYPE" => "",
            "ELEMENT_WIDTH_CSS" => "",
            "MARGIN_CSS" => "",
            "VERTICAL_ALIGN" => ""
        ]
    ],


    "TILE" => [
        "TYPE" => "",
        "PARALLAX" => "",
        "VERTICAL_ALIGN" => "",
        "HORIZONTAL_MARGIN" => "",
        "VERTICAL_MARGIN" => "",
    ],

    "SLIDER" => [
        "SHOW_ARROWS" => "",
        "SHOW_BULLETS" => "",
        "AUTOPLAY" => "",
        "AUTOPLAY_INTERVAL" => "",
        "CENTERED" => "",
        "MOVE_SETS" => "",
        "HIDE_CONTENT" => "",
        "CONTENT_ON_PICTURE" => "",
    ]

])?>
 */

class COipIblockElementList extends \CBitrixComponent
{


    /** @var array */
    protected $rawData = [];

    public function onPrepareComponentParams($arParams)
    {
        $arParams = $this->initCommonParams($arParams);
        return $this->initPersonalParams($arParams);
    }


    public function executeComponent()
    {
        $this->execute();

        $elements = [];
        foreach($this->rawData as $item) {
            $elements[$item["FIELDS"]["ID"]] = new Element($item);
        }

        if(!count($elements)) {
            $this->arResult["ERRORS"][] = "Ошибка: элементы не найдены";
        }
        else {
            $this->arResult["ELEMENTS"] = $elements;
        }

        $this->includeComponentTemplate();
    }

    protected function execute() {

    if(empty($this->arResult["EXCEPTION"])) {
        try {

                if (!\Bitrix\Main\Loader::includeModule("iblock")) {
                    throw new \Bitrix\Main\SystemException("Module iblock is not installed");
                }

                $this->fetchCommonData()->fetchCommonPictures()->getComplicatedProps();

            }
            catch (LoaderException $e) {
                $this->arResult["EXCEPTION"] = $e->getMessage();
            }
            catch (SystemException $e) {
                $this->arResult["EXCEPTION"] = $e->getMessage();
            }
        }
    }

    /**
     * @return array
     * @param  $arParams
     * @throws ArgumentNullException | ArgumentTypeException
     */
    protected function initCommonParams($arParams) {

        try {
            if(!is_set($arParams["IBLOCK_ID"])) {
                throw new ArgumentNullException("IBLOCK_ID");
            }

            if(!intval($arParams["IBLOCK_ID"])) {
                throw new ArgumentTypeException("IBLOCK_ID");
            }
        }
        catch (\Bitrix\Main\ArgumentException $e) {
            $this->arResult["EXCEPTION"] = $e->getMessage();
        }

        $this->setDefaultParam($arParams["SECTION_ID"],0);
        $this->setDefaultParam($arParams["RESIZE_FILE_PROPS"],["width" => 600, "height" => 600]);
        $this->setDefaultBooleanParam($arParams["SHOW_INACTIVE"]);
        $this->setDefaultParam( $arParams["PROPERTIES"],[]);
        
        $this->setDefaultParam( $arParams["COUNT"],24);
        $this->setDefaultParam( $arParams["FILTER"],"");
        $this->setDefaultParam( $arParams["SORT_1"],"");
        $this->setDefaultParam( $arParams["SORT_2"],"");
        $this->setDefaultBooleanParam( $arParams["SHOW_META"]);
        $this->setDefaultBooleanParam( $arParams["INCLUDE_IBLOCK_CHAIN"],"");

        $this->setDefaultBooleanParam( $arParams["SHOW_404"],true);
        $this->setDefaultBooleanParam( $arParams["SHOW_PAGER"],true);
        $this->setDefaultBooleanParam( $arParams["SHOW_SIDEBAR"]);


        if(is_array($arParams["PROPERTIES"])) {
            $arParams["PROPERTIES"] = $this->trimPropCodes($arParams["PROPERTIES"]);
        }

        return $arParams;
    }

    /**
     * @param  $arParams
     * @return array
     * @throws ArgumentNullException | ArgumentTypeException
     */
    protected function initPersonalParams($arParams) {
       $this->setDefaultParam($arParams["LIST_VIEW_TITLE_TEXT"],"");
       $this->setDefaultParam($arParams["LIST_VIEW_TITLE_TAG"],"div");
       $this->setDefaultParam($arParams["LIST_VIEW_TITLE_CSS"],"uk-h1");
       $this->setDefaultParam($arParams["LIST_VIEW_TITLE_ICON_CSS"],"");
       $this->setDefaultParam($arParams["LIST_VIEW_TITLE_ALIGN"],"uk-text-left");

       $this->setDefaultParam($arParams["LIST_VIEW_WRAP_COLOR"],"uk-section-default");
       $this->setDefaultParam($arParams["LIST_VIEW_WRAP_SIZE"],"uk-section-small");
       $this->setDefaultParam($arParams["LIST_VIEW_WRAP_ADD_CSS"],"");

       $this->setDefaultParam($arParams["LIST_VIEW_CONTAINER_WIDTH_CSS"],"uk-container-expand");
       $this->setDefaultParam($arParams["LIST_VIEW_CONTAINER_TYPE"],"TILE");
       $this->setDefaultParam($arParams["LIST_VIEW_CONTAINER_ELEMENT_WIDTH_CSS"],
           "uk-child-width-1-1 uk-child-width-1-2@m uk-child-width-1-3@l uk-child-width-1-4@xl");
        $this->setDefaultParam($arParams["LIST_VIEW_CONTAINER_MARGIN_CSS"],"uk-grid-medium");
        $this->setDefaultBooleanParam($arParams["LIST_VIEW_CONTAINER_VERTICAL_ALIGN"],true);

        $this->setDefaultParam($arParams["TILE_DYNAMIC"], "false");
        $this->setDefaultParam($arParams["TILE_PARALLAX"],0);
        $this->setDefaultParam($arParams["TILE_VERTICAL_ALIGN"],"uk-flex-left@m");
        $this->setDefaultParam($arParams["TILE_HORIZONTAL_MARGIN"],
            "uk-grid-column-medium-left@m");
        $this->setDefaultParam($arParams["TILE_VERTICAL_MARGIN"],
            "uk-grid-row-medium");

        $this->setDefaultBooleanParam($arParams["SLIDER_SHOW_ARROWS"],true);
        $this->setDefaultBooleanParam($arParams["SLIDER_SHOW_BULLETS"]);
        $this->setDefaultParam($arParams["SLIDER_AUTOPLAY"],"true");
        $this->setDefaultParam($arParams["SLIDER_AUTOPLAY_INTERVAL"],6000);
        $this->setDefaultParam($arParams["SLIDER_CENTERED"],"false");
        $this->setDefaultParam($arParams["SLIDER_MOVE_SETS"],"false");
        $this->setDefaultBooleanParam($arParams["SLIDER_HIDE_CONTENT"]);
        $this->setDefaultBooleanParam($arParams["SLIDER_CONTENT_ON_PICTURE"]);

        return $arParams;
    }

    /**
     * @param mixed $param
     * @param mixed $defaultValue
     * @return mixed
     */
    protected function setDefaultParam(&$param, $defaultValue) {

        if(!is_set($param)) {
            $param = $defaultValue;
        }

    }

    /**
     * @param mixed $param
     * @param boolean $defaultValue
     * @return mixed
     */
    protected function  setDefaultBooleanParam(&$param, $defaultValue = false) {

        switch($defaultValue) {
            case true:
            if(!is_set($param) || $param !== "N") {
                $param = "Y";
            }
            break;

            default:
                if(!is_set($param) || $param !== "Y") {
                    $param = "N";
                }
            break;
        }

    }

    /**
     * @param array $propCodes
     * @return array
     */
    protected function trimPropCodes($propCodes) {
        return array_map(function ($propCode) {
            return trim($propCode);
        }, $propCodes);
    }

    /** @return array */
    protected function consistFilter()
    {
        $filter = [
            "IBLOCK_ID" => $this->arParams["IBLOCK_ID"]
        ];

        if (intval($this->arParams["SECTION_ID"])) {
            $filter["SECTION_ID"] = $this->arParams["SECTION_ID"];
        }

        if ($this->arParams["SHOW_INACTIVE"] !== "Y") {
            $filter["ACTIVE"] = "Y";
        }

        return $filter;
    }

    /** @return self */
    protected function fetchCommonData()
    {

        $arParams = $this->arParams;

        $order = [];
        $filter = $this->consistFilter();

        $group = false;
        $navStartParams = false;
        $select = ["ID", "IBLOCK_ID", "SECTION_ID", "NAME", "ACTIVE", "ACTIVE_FROM", "ACTIVE_TO", "SORT", "PREVIEW_PICTURE", "DETAIL_PICTURE", "PREVIEW_TEXT",
            "DETAIL_TEXT", "LIST_PAGE_URL", "SECTION_PAGE_URL", "DETAIL_PAGE_URL"];

        $propIDs = [];
        if($arParams["PROPERTIES"] === "all") {
            $propIDs = "all";
        }
        elseif(is_array($arParams["PROPERTIES"])) {
            $propIDs = $this->fetchPropIDs($arParams["PROPERTIES"]);
        }

        $this->rawData = $this->getRows(\CIBlockElement::GetList($order, $filter, $group, $navStartParams, $select),
            $propIDs);

       return $this;
    }

    /** @return self */
    protected function fetchCommonPictures() {

        $pictureIDs = "";

        foreach ($this->rawData as $key => $item) {
           if($item["FIELDS"]["DETAIL_PICTURE"]) {
               $pictureIDs .= $item["FIELDS"]["DETAIL_PICTURE"].",";
           }

           if($item["FIELDS"]["PREVIEW_PICTURE"]) {
               $pictureIDs .= $item["FIELDS"]["PREVIEW_PICTURE"].",";
           }
        }

        $files = [];
        $dbRes = \CFile::GetList([],["@ID" => $pictureIDs]);
        while($file = $dbRes->GetNext()) {
            $files[$file["ID"]] = $file;
        }

        foreach ($this->rawData as $key => $item) {

            $previewPictureID = $this->rawData[$key]["FIELDS"]["PREVIEW_PICTURE"];
            $detailPictureID = $this->rawData[$key]["FIELDS"]["DETAIL_PICTURE"];

            if($previewPictureID) {
                $this->rawData[$key]["FIELDS"]["PREVIEW_PICTURE"] =  $files[$previewPictureID];
            }
            if($detailPictureID) {
                $this->rawData[$key]["FIELDS"]["DETAIL_PICTURE"] =  $files[$detailPictureID];
            }
        }

        return $this;
    }

    /** @param string $fileID  */
    protected function fetchPicture($fileID) {

        return \CFile::ResizeImageGet(
            $fileID,
            $this->arParams["RESIZE_FILE_PROPS"],
            BX_RESIZE_IMAGE_PROPORTIONAL,
            true
        );
    }

    /** @return self */
    protected function getComplicatedProps() {

        $this->getFileProps();

        return $this;
    }

    /** @return self */
    protected function getFileProps() {

        $fileProps = [];

        foreach ($this->rawData as $key => $item) {
            foreach ($item["PROPS"] as $propCode => $prop) {
                if($prop["PROPERTY_TYPE"] == "F" && $prop["VALUE"]) {
                    $fileProps[$item["FIELDS"]["ID"]][$prop["ID"]] = $prop["VALUE"];
                }
            }
        }

        if(!empty($fileProps)) {
            foreach ($fileProps as $elementID => $elementFileProps) {
                foreach($elementFileProps as $propId => $propValue) {

                    if(is_array($propValue)) {

                        foreach($propValue as $key =>  $fileID) {
                            $fileProps[$elementID][$propId][$key] = $this->fetchPicture($fileID);
                        }

                    }
                    else {
                        $fileProps[$elementID][$propId] = $this->fetchPicture($propValue);
                    }

                }
            }
        }

        foreach ($this->rawData as $key => $item) {
            foreach ($item["PROPS"] as $propCode => $prop) {
                if($prop["PROPERTY_TYPE"] == "F" && $prop["VALUE"]) {
                    $this->rawData[$key]["PROPS"][$propCode]["VALUE"] =  $fileProps[$item["FIELDS"]["ID"]][$prop["ID"]];
                }
            }
        }

        return $this;
    }

    /**
     * @param array
     * @return array
     */
    protected function fetchPropIDs($propCodes) {
        $propIDs = [];

        $dbProps = \CIBlockProperty::GetList([],["IBLOCK_ID" => $this->arParams["IBLOCK_ID"]]);
        while($prop = $dbProps->GetNext()) {
            if(in_array($prop["CODE"],$propCodes)) {
                $propIDs[$prop["CODE"]] = $prop["ID"];
            }
        }

        return $propIDs;
    }

    /**
     * @param \CIblockResult $iblockResult
     * @param string|array $propIds
     * @return array
     */
    protected function getRows($iblockResult, $propIds) {
        $arResult = [];

        while ($object = $iblockResult->GetNextElement()) {
            $result["FIELDS"] = $object->GetFields();

            if(!empty($propIds)) {
                if(is_string($propIds)) {
                    $result["PROPS"] = $object->GetProperties();
                }
                else {
                    $result["PROPS"] = $object->GetProperties([],["ID" => $propIds]);
                }
            }

            $arResult[] = $result;
        }

        return $arResult;
    }

    /** @return  array */
    public function getParams() {
        return $this->arParams;
    }

    /**
     * @param string $paramCode
     * @return mixed
     */
    public function getParam($paramCode) {
       return $this->getParamRecursive($paramCode, $this->arParams);

    }

    /**
     * @param string $paramCode
     * @return boolean
     */
    public function isParam($paramCode) {
        return ($this->getParam($paramCode) === "Y") ? true : false;
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

    /** @return  boolean */
    public function isContainerSlider() {
        return ($this->arParams["LIST_VIEW_CONTAINER_TYPE"]
            && $this->arParams["LIST_VIEW_CONTAINER_TYPE"]  === "SLIDER");
    }

    public function getCardPositionCss() {
       $picPosition = $this->getParam("ELEMENT_VIEW_PICTURE_POSITION");

       $result = "";

       switch($picPosition) {

           case "bottom":
               $result = "uk-flex-column uk-flex-column-reverse";
           break;

           case "left":
               $result = "uk-flex-row uk-flex-middle uk-child-width-1-2";
           break;

           case "right":
               $result = "uk-flex-row uk-flex-row-reverse uk-flex-middle uk-child-width-1-2";
           break;

           default:
               $result = "uk-flex-column";
           break;
       }

       return $result;
    }

    /**
     * @param string $videoLink
     * @return mixed
     */
    public function getConvertedVideo($videoLink) {
        return str_replace("watch?v=", "embed/", $videoLink);
    }

}