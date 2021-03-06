<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Oip\Custom\Component\Iblock\Element;

use Oip\RelevantProducts\DataWrapper;
use Oip\RelevantProducts\DBDataSource;
use Oip\CacheInfo;
use Oip\Util\Cache\BXCacheService;

use Oip\GuestUser\Handler as GuestService;

\CBitrixComponent::includeComponentClass("oip:iblock.element.list");

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

        $this->addElementView($this->arResult["ELEMENT"]->getId());


        return ($this->arResult["ELEMENT"]) ? $this->arResult["ELEMENT"]->getId() : null;
    }

    /**
     * @inheritdoc
    */
    protected function initParams($arParams)
    {
        $arParams = parent::initParams($arParams);
        $this->setDefaultParam($arParams["ELEMENT_CODE"],"");

        try {
            if(!$arParams["ELEMENT_CODE"] && !is_set($arParams["ELEMENT_ID"])) {
                throw new \Bitrix\Main\ArgumentNullException("ELEMENT_ID");
            }

            if(!$arParams["ELEMENT_CODE"] && !intval($arParams["ELEMENT_ID"])) {
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

        if($this->getParam("ELEMENT_CODE")) {
           $filter["CODE"] = $this->getParam("ELEMENT_CODE");
        }
        else {
            $filter["ID"] = $this->getParam("ELEMENT_ID");
        }

        if($this->arParams["SECTION_ID"]) {
           unset($filter["SECTION_ID"]);
        }

        unset($filter["!PROPERTY_" . $this->getParam("QUANTITY_PROP")]);

        return $filter;
    }

    protected function addElementView($elementID) {
        try {

            global $DB;
            global $USER;

            $cacheInfo = new CacheInfo();
            $cacheService = new BXCacheService();
            $ds = new DBDataSource($DB, $cacheInfo, $cacheService);
            $dw = new DataWrapper($ds);

            $userID = $USER->GetID();

            if(!$USER->IsAuthorized()) {
                /**
                 * @var $OipGuestUser GuestService
                 */
                global $OipGuestUser;
                $userID = $OipGuestUser->getUser()->getNegativeId();
            }

            $dw->addProductView((int)$userID, (int)$elementID);

        }
        catch(\Exception $exception) {
            echo "<p>Не удалось обработать просмотр товара: {$exception->getMessage()}</p>";
        }
    }

    protected function getSectionData()
    {
        $item = reset($this->rawData);
        $sectionId = ($item["FIELDS"]["IBLOCK_SECTION_ID"]) ?? 0;

        if($sectionId > 0) {

            global $APPLICATION;
            $sectionData =  $APPLICATION->IncludeComponent(
                "oip:iblock.section.list",
                "",
                [
                    "IBLOCK_ID" => $this->getParam("IBLOCK_ID"),
                    "BASE_SECTION" => (int)$sectionId,
                    "DEPTH" => 0,
                    "IS_CACHE" => $this->getParam("IS_CACHE"),
                    "CACHE_TIME" => $this->getParam("CACHE_TIME"),
                    "INCLUDE_TEMPLATE" => false,
                    "COUNT_VIEW" => false,
                    "USER_FIELDS" => ["UF_*"],
                ]
            );

            $this->rewriteComponentParams("CARD_VIEW_SHOW_SIDEBAR",
                $sectionData["UF_SIDEBAR_ELEMENT"], true);
            $this->rewriteComponentParams("CARD_VIEW_SHOW_SAME_ELEMENT",
                $sectionData["UF_SAME_ELEMENT"], true);
            $this->rewriteComponentParams("CARD_VIEW_SHOW_POPULAR_WITH_THIS",
                $sectionData["UF_POPULAR_WITH_THIS"], true);

            $this->rawData[0]["FIELDS"]["SECTION_NAME"] = $sectionData["SECTION_NAME"];
        }
    }
}