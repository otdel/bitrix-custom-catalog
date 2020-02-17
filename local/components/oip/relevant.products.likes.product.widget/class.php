<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\ArgumentException;
use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\ArgumentTypeException;

\CBitrixComponent::includeComponentClass("oip:relevant.products");

class CRelevantProductsLikesProductWidget extends \CRelevantProducts
{
    /**
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
                $userId = $this->getUserId();
                $productId = $this->getParam("PRODUCT_ID");

                $this->arResult["LIKES"] = $this->dw->getProductLikes($productId);
                $this->arResult["IS_LIKED"] = $this->dw->isProductLikedByUser($productId, $userId);
            }
            catch(Exception $e) {
                $this->arResult["EXCEPTION"] = $e;
            }
        }

        $this->includeComponentTemplate();

    }
}