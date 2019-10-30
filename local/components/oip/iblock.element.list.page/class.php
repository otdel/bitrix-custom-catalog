<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

\CBitrixComponent::includeComponentClass("oip:iblock.element.list");

class COipIblockElementListPage extends COipIblockElementList {
    public function executeComponent() {

        $this->includeComponentTemplate();
    }

    /**
     * @param  $arParams
     * @return array
     */
    protected function initPersonalParams($arParams) {
        $arParams = parent::initPersonalParams($arParams);
        $this->setDefaultBooleanParam( $arParams["SHOW_SIDEBAR"]);
        $this->setDefaultParam( $arParams["SIDEBAR_WIDTH"], "");

        $this->setDefaultBooleanParam($arParams["SHOW_SORT"]);

        $this->setDefaultBooleanParam( $arParams["SHOW_PAGER"],true);
        $this->setDefaultParam( $arParams["PAGER_TYPE"],"LOAD_MORE");

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
}