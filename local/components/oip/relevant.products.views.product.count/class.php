<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\ArgumentException;
use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\ArgumentTypeException;

\CBitrixComponent::includeComponentClass("oip:relevant.products");

class CRelevantProductsViewsProductCount extends \CRelevantProducts
{
    /**
     * @param array $arParams
     * @return array
     * @thrown ArgumentNullException
     * @thrown ArgumentTypeException
     */
    protected function initParams($arParams) {

        $arParams = parent::initParams($arParams);

        try {
            if(!is_set($arParams["PRODUCT_ID"])) {
                throw new ArgumentNullException("PRODUCT_ID");
            }

            if(!intval($arParams["PRODUCT_ID"])) {
                throw new ArgumentTypeException("PRODUCT_ID");
            }
        }
        catch (ArgumentException $e) {
            $this->arResult["EXCEPTION"] = $e;
        }

        return $arParams;
    }

    public function executeComponent()
    {
        if(!is_set($this->arResult["EXCEPTION"])) {
            try {
                $productId = $this->getParam("PRODUCT_ID");

                $this->arResult["VIEWS"] = $this->dw->getProductViews($productId);
            }
            catch(Exception $e) {
                $this->arResult["EXCEPTION"] = $e;
            }
        }

        $this->includeComponentTemplate();

    }
}