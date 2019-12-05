<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

\CBitrixComponent::includeComponentClass("oip:component");

class COipPageNavigation extends \COipComponent
{

    protected function initParams($arParams)
    {
        $arParams = parent::initParams($arParams);

        if(!is_set($arParams["NAV_ID"])) {
            throw new \Bitrix\Main\ArgumentNullException("NAV_ID");
        }

        if(!is_set($arParams["PAGES"])) {
            throw new \Bitrix\Main\ArgumentNullException("PAGES");
        }

        if(!is_set($arParams["PAGE"])) {
            $arParams["PAGE"] = 1;
        }

        return $arParams;
    }

    public function executeComponent()
    {

        if(empty($this->arResult["EXCEPTION"])) {

            global $APPLICATION;

            $curParams = $APPLICATION->GetCurParam();

            if($curParams) {
                $arCurParams = explode("&",$curParams);
                $this->arResult["PARAMS"] = $this->getParamsArray($arCurParams);
            }
            else {
                $this->arResult["PARAMS"] = "";
            }
        }

        $this->includeComponentTemplate();
    }

    private function getParamsArray($arCurParams) {

        $arParamsByKeys = [];

        foreach ($arCurParams as $paramString) {
            $paramArray = explode("=", $paramString);
            $arParamsByKeys[$paramArray[0]] = $paramArray[1];
        }

        return $arParamsByKeys;
    }

    public function generateLink($arParamsByKeys, $navId, $pageNumber) {

        $link = "";

        if($arParamsByKeys) {
            $arParamsByKeys["page_".$navId] = $pageNumber;

            foreach ($arParamsByKeys as $paramKey => $paramValue) {
                if($link) {
                    $link .= "&".$paramKey."=".$paramValue;
                }
                else {
                    $link = "?".$paramKey."=".$paramValue;
                }
            }
        }
        else {
            $link = "?page_".$navId."=".$pageNumber;
        }

        return $link;
    }
}