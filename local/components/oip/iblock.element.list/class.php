<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

require_once(__DIR__."/../Element.php");
require_once(__DIR__."/../Property.php");

use \Bitrix\Main\LoaderException;
use \Bitrix\Main\SystemException;
use Oip\Custom\Component\Iblock\Element;

\CBitrixComponent::includeComponentClass("oip:iblock.element");

class COipIblockElementList extends \COipIblockElement
{

    /** @var array */
    protected $rawData = [];

    /** @var array */
    protected $pagination = [];

    /** @return array */
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

        return $this->pagination;
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

        $order = [
            $this->getParam("SORT_1") => $this->getParam("BY_1"),
            $this->getParam("SORT_2") => $this->getParam("SORT_2")
        ];
        $filter = $this->consistFilter();

        $group = false;
        $pageNumber = $this->getPageNumber($this->componentId);

        $navStartParams = [
            "iNumPage" =>  ($pageNumber) ? $pageNumber : 1,
            "bShowAll" => false,
            "nPageSize" => $this->getParam("COUNT")
        ];

        $select = ["ID", "IBLOCK_ID", "SECTION_ID", "NAME", "ACTIVE", "ACTIVE_FROM", "ACTIVE_TO", "SORT", "PREVIEW_PICTURE", "DETAIL_PICTURE", "PREVIEW_TEXT",
            "DETAIL_TEXT", "LIST_PAGE_URL", "SECTION_PAGE_URL", "DETAIL_PAGE_URL"];

        $propIDs = [];
        if($arParams["PROPERTIES"] === "all") {
            $propIDs = "all";
        }
        elseif(is_array($arParams["PROPERTIES"])) {
            $propIDs = $this->fetchPropIDs($arParams["PROPERTIES"]);
        }

        $fetchFunction = function () use($order, $filter, $group, $navStartParams, $select) {
           return \CIBlockElement::GetList($order, $filter, $group, $navStartParams, $select);
        };

        if($this->isCache()) {
            $cacheId = $this->getCacheId().$pageNumber;
            $dbResult = $this->cacheService($fetchFunction, $cacheId);
        }
        else {
            $dbResult = $fetchFunction();
        }

        $arResult = $this->getRows($dbResult, $propIDs);

        $this->rawData = $arResult["ITEMS"];
        $this->pagination = $arResult["PAGINATION"];

        return $this;
    }

    /**
     * @param int $navId
     * @param int $navId
     * @return int
     *
     * @throws SystemException
     */
    protected function getPageNumber($navId) {
        $pageNumber = \Bitrix\Main\Application::getInstance()->getContext()->getRequest()->get("page_".$navId);

        return (int) ($pageNumber) ? $pageNumber : 1;
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

        $fetchFunction = function () use($pictureIDs) {
            $files = [];
            $dbRes = \CFile::GetList([],["@ID" => $pictureIDs]);
            while($file = $dbRes->GetNext()) {
                $files[$file["ID"]] = $file;
            }

            return $files;
        };

        if($pictureIDs && $this->isCache()) {
            $cacheId = $this->getCacheId().$pictureIDs;
            $files = $this->cacheService($fetchFunction, $cacheId);
        }
        else {
            $files = $fetchFunction();
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

    /**
     * @param string $fileID
     * @return array
     */
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

            $arResult["ITEMS"][] = $result;
        }

        $arResult["PAGINATION"]["NAV_ID"] = $this->componentId;
        $arResult["PAGINATION"]["PAGES"] = $iblockResult->NavPageCount;
        $arResult["PAGINATION"]["PAGE"] = $iblockResult->NavPageNomer;
        $arResult["PAGINATION"]["PAGE_SIZE"] = $iblockResult->NavPageSize;
        $arResult["PAGINATION"]["RECORDS_COUNT"] = (float) $iblockResult->NavRecordCount;

        return $arResult;
    }

    /** @return  boolean */
    public function isContainerSlider() {
        return ($this->arParams["LIST_VIEW_CONTAINER_TYPE"]
            && $this->arParams["LIST_VIEW_CONTAINER_TYPE"]  === "SLIDER");
    }

    public function getCardPositionCss() {
       $picPosition = $this->getParam("ELEMENT_VIEW_PICTURE_POSITION");

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