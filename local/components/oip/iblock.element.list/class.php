<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use \Bitrix\Main\ArgumentNullException;
use \Bitrix\Main\ArgumentTypeException;
use \Bitrix\Main\LoaderException;
use \Bitrix\Main\SystemException;

/**
 *
 * <?$APPLICATION->IncludeComponent("oip:iblock.element.list","",[
    "IBLOCK_ID" => 2,
    "SECTION_ID" => 8,
    "PROPERTIES" => [9,8,13,14],
    ])?>
 */

class COipIblockElementList extends \CBitrixComponent
{

    public function onPrepareComponentParams($arParams)
    {

        return  $this->initParams($arParams);
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
        $this->fetchCommonData()->fetchCommonPictures()->getComplicatedProps();
    }

    /**
     * @return array
     * @throws ArgumentNullException | ArgumentTypeException
     */
    protected function initParams($arParams) {

        try {
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

        if(!is_set($arParams["PROPERTIES"])) {
            $arParams["PROPERTIES"] = [];
        }

        if(!is_set($arParams["SECTION_ID"])) {
            $arParams["SECTION_ID"] = 0;
        }

        if(!is_set($arParams["RESIZE_FILE_PROPS"])) {
            $arParams["RESIZE_FILE_PROPS"] = ["width" => 600, "height" => 600];
        }

        return $arParams;
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
        $select = ["ID", "IBLOCK_ID", "NAME","PREVIEW_PICTURE", "DETAIL_PICTURE", "PREVIEW_TEXT",
            /*"DETAIL_TEXT",*/ "LIST_PAGE_URL", "SECTION_PAGE_URL", "DETAIL_PAGE_URL"];

        $this->arResult = $this->getRows(\CIBlockElement::GetList($order, $filter, $group, $navStartParams, $select),
            $arParams["PROPERTIES"]);

       return $this;
    }

    /** @return self */
    protected function fetchCommonPictures() {

        $pictureIDs = "";

        foreach ($this->arResult as $key => $item) {
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

        foreach ($this->arResult as $key => $item) {

            $previewPictureID = $this->arResult[$key]["FIELDS"]["PREVIEW_PICTURE"];
            $detailPictureID = $this->arResult[$key]["FIELDS"]["DETAIL_PICTURE"];

            if($previewPictureID) {
                $this->arResult[$key]["FIELDS"]["PREVIEW_PICTURE"] =  $files[$previewPictureID];
            }
            if($detailPictureID) {
                $this->arResult[$key]["FIELDS"]["DETAIL_PICTURE"] =  $files[$detailPictureID];
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

        foreach ($this->arResult as $key => $item) {
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

        foreach ($this->arResult as $key => $item) {
            foreach ($item["PROPS"] as $propCode => $prop) {
                if($prop["PROPERTY_TYPE"] == "F" && $prop["VALUE"]) {
                    $this->arResult[$key]["PROPS"][$propCode]["VALUE"] =  $fileProps[$item["FIELDS"]["ID"]][$prop["ID"]];
                }
            }
        }

        return $this;
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