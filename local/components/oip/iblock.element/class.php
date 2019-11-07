<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\ArgumentTypeException;

\CBitrixComponent::includeComponentClass("oip:component");

abstract class COipIblockElement extends \COipComponent
{
    /**
     * @return array
     * @param  $arParams
     * @throws ArgumentNullException | ArgumentTypeException
     */
    protected function initParams($arParams) {

        $arParams = parent::initParams($arParams);

        if(!is_set($arParams["IBLOCK_ID"])) {
            throw new ArgumentNullException("IBLOCK_ID");
        }

        if(!intval($arParams["IBLOCK_ID"])) {
            throw new ArgumentTypeException("IBLOCK_ID");
        }

        $this->setDefaultParam($arParams["SECTION_ID"],0);
        $this->setDefaultParam($arParams["SECTION_NAME"],"");
        $this->setDefaultParam($arParams["RESIZE_FILE_PROPS"],["width" => 600, "height" => 600]);
        $this->setDefaultBooleanParam($arParams["SHOW_INACTIVE"]);
        $this->setDefaultParam( $arParams["PROPERTIES"],[]);

        $this->setDefaultBooleanParam( $arParams["SHOW_META"]);
        $this->setDefaultBooleanParam( $arParams["INCLUDE_IBLOCK_CHAIN"]);

        $this->setDefaultBooleanParam( $arParams["CHECK_PERMISSIONS"],true);

        $this->setDefaultBooleanParam( $arParams["SHOW_404"],true);

        if(is_array($arParams["PROPERTIES"])) {
            $arParams["PROPERTIES"] = $this->trimPropCodes($arParams["PROPERTIES"]);
        }

        return $arParams;
    }

    /**
     * @param array $propCodes
     * @return array
     */
    protected function trimPropCodes($propCodes) {
        return array_map(function ($propCode) {
            return trim($propCode);
        }, $propCodes);
    }
}