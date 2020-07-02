<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\ArgumentNullException;

\CBitrixComponent::includeComponentClass("oip:component");

class COipFilterForm extends \COipComponent {

    protected function initParams($arParams)
    {
        $arParams =  parent::initParams($arParams);

        if(!is_set($arParams["FILTER_ID"])) {
            throw new ArgumentNullException("FILTER_ID");
        }

        if(!is_set($arParams["IBLOCK_ID"])) {
            throw new ArgumentNullException("IBLOCK_ID");
        }

        $this->setDefaultParam($arParams["SOURCE"],"get");
        $this->setDefaultParam($arParams["MODE"],"iblock");

        return $arParams;
    }

    public function executeComponent()
    {
        $this->includeComponentTemplate();

        global $APPLICATION;

        return $APPLICATION->IncludeComponent("oip:filter.processor","",[
            "FILTER_ID" => $this->getParam("FILTER_ID"),
        ]);
    }
}