<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

\CBitrixComponent::includeComponentClass("oip:iblock.element");

class COipIblockElementPage extends \COipIblockElement {
    public function executeComponent() {
        $this->includeComponentTemplate();
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
}