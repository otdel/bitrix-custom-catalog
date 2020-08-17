<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\ArgumentTypeException;
use Bitrix\Main\Config\Configuration;

\CBitrixComponent::includeComponentClass("oip:component");

class CSystemExceptionViewer extends \COipComponent {

    protected function initParams($arParams)
    {
        $arParams =  parent::initParams($arParams);

        if(!is_set($arParams["DEBUG_MODE"])) {
            $arParams["DEBUG_MODE"] = Configuration::getValue("oip_debug_mode");
        }

        $this->setDefaultBooleanParam($arParams["DEBUG_MODE"]);
        $this->setDefaultParam($arParams["EXCEPTION"], "");

        if($arParams["EXCEPTION"] && !is_string($arParams["EXCEPTION"])) {
            try {
                if(!($arParams["EXCEPTION"] instanceof Exception)) {
                    throw new ArgumentTypeException("EXCEPTION");
                }
            }
            catch(Exception $e) {
                $this->arResult["EXCEPTION"] = $e;
            }
        }

        $this->arResult["EXCEPTION"] = $arParams["EXCEPTION"];

        return $arParams;
    }

    public function executeComponent()
    {
        $this->includeComponentTemplate();
    }
}

