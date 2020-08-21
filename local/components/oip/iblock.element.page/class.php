<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

\CBitrixComponent::includeComponentClass("oip:iblock.element");

class COipIblockElementPage extends \COipIblockElement {
    public function executeComponent() {
        $this->includeComponentTemplate();
        // Формируем фильтр
        $this->arParams["FILTER"] = !empty($this->arParams["FILTER"]) ? array_merge($this->arParams["FILTER"], $this->consistFilter()) : $this->consistFilter();
    }

    public function getComponentId() {
        return $this->componentId;
    }

    public function getPageNumber($navId) {
        $pageNumber = \Bitrix\Main\Application::getInstance()->getContext()->getRequest()->get("page_".$navId);

        return (int) ($pageNumber) ? $pageNumber : 1;
    }

    /**
     * @param  $arParams
     * @return array
     */
    protected function initParams($arParams) {

        $arParams = parent::initParams($arParams);

        $this->setDefaultBooleanParam( $arParams["SHOW_SIDEBAR"]);
        $this->setDefaultParam( $arParams["SIDEBAR_WIDTH"], "");

        $this->setDefaultBooleanParam($arParams["SHOW_SORT"]);

        $this->setDefaultBooleanParam( $arParams["SHOW_PAGER"],true);
        $this->setDefaultParam( $arParams["PAGER_TYPE"],"LOAD_MORE");

        $this->setDefaultParam($arParams["BRANDS_IBLOCK_ID"], 0);
        $this->setDefaultParam($arParams["TAGS_IBLOCK_ID"], 0);

        /*---------------------------------------------------------------------------------*/

        return $arParams;
    }

    /**
     * @return string
     */
    public function getPagerType() {
        return ($this->getParam("PAGER_TYPE") === "PAGES") ? "PAGES" : "LOAD_MORE";
    }

    /**
     * @return boolean
     */
    public  function  isPagerTypeLoadMore() {
        return ($this->getPagerType() === "LOAD_MORE");
    }

    /** @return array */
    protected function consistFilter()
    {
        $filter = [
            "CHECK_PERMISSIONS" => $this->getParam("CHECK_PERMISSIONS"),
            "IBLOCK_ID" => $this->arParams["IBLOCK_ID"]
        ];

        if (intval($this->arParams["SECTION_ID"])) {
            $filter["SECTION_ID"] = $this->arParams["SECTION_ID"];
            $filter["INCLUDE_SUBSECTIONS"] = "Y";
        }

        if ($this->arParams["SHOW_INACTIVE"] !== "Y") {
            $filter["ACTIVE"] = "Y";
        }

        if(!$this->isParam("SHOW_ZERO_QUANTITY") && $this->getParam("QUANTITY_PROP")) {
            $filter["!PROPERTY_" . $this->getParam("QUANTITY_PROP")] = false;
        }

        if(!empty($this->arParams["FILTER"])) {
            $filter = array_merge($filter, $this->arParams["FILTER"]);
        }

        return $filter;
    }
}