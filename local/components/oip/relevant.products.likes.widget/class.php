<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\ArgumentException;
use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\ArgumentTypeException;

\CBitrixComponent::includeComponentClass("oip:relevant.products");

abstract class CRelevantProductsLikeWidget extends \CRelevantProducts
{
    protected function initParams($arParams, $fieldName)
    {
        $arParams = parent::initParams($arParams);

        try {
            if (!is_set($arParams[$fieldName])) {
                throw new ArgumentNullException($fieldName);
            }

            if (!intval($arParams[$fieldName])) {
                throw new ArgumentTypeException($fieldName);
            }
        } catch (ArgumentException $e) {
            $this->arResult["EXCEPTION"] = $e;
        }

        return $arParams;
    }

    public function executeComponent()
    {
        if(!is_set($this->arResult["EXCEPTION"])) {
            try {
                $this->execute();
            }
            catch(Exception $e) {
                $this->arResult["EXCEPTION"] = $e;
            }
        }

        $this->includeComponentTemplate();
    }
}