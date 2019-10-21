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
 *
 * <?$APPLICATION->IncludeComponent("oip:iblock.element.list","",[
    "IBLOCK_ID" => 2,
    "SECTION_ID" => 8,
    "SHOW_INACTIVE" => "Y"
    "PROPERTIES" => [
        "PICS_NEWS",
        "TEST_STRING",
        "TEST_FILE",
        "TEST_LIST",
    ],
    "RESIZE_FILE_PROPS" => [600,600]
])?>
 */

class COipIblockElementList extends \CBitrixComponent
{


    /** @var array */
    protected $rawData = [];

    public function onPrepareComponentParams($arParams)
    {

        return  $this->initParams($arParams);
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
     * @throws ArgumentNullException | ArgumentTypeException
     */
    protected function initParams($arParams) {

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

        if(!is_set($arParams["PROPERTIES"])) {
            $arParams["PROPERTIES"] = [];
        }
        else {
            $arParams["PROPERTIES"] = $this->trimPropCodes($arParams["PROPERTIES"]);
        }

        if(!is_set($arParams["SECTION_ID"])) {
            $arParams["SECTION_ID"] = 0;
        }

        if(!is_set($arParams["RESIZE_FILE_PROPS"])) {
            $arParams["RESIZE_FILE_PROPS"] = ["width" => 600, "height" => 600];
        }

        if(!is_set($arParams["SHOW_INACTIVE"])) {
            $arParams["SHOW_INACTIVE"] = "N";
        }

        return $arParams;
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

        $propIDs = $this->fetchPropIDs($arParams["PROPERTIES"]);

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
     * @param array $propIds
     * @return array
     */
    protected function getRows($iblockResult, $propIds) {
        $arResult = [];

        while ($object = $iblockResult->GetNextElement()) {
            $result["FIELDS"] = $object->GetFields();

            if(!empty($propIds)) {
                $result["PROPS"] = $object->GetProperties([],["ID" => $propIds]);
            }

            $arResult[] = $result;
        }

        return $arResult;
    }
}