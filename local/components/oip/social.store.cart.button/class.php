<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

\CBitrixComponent::includeComponentClass("oip:social.store.cart");

use Bitrix\Main\ArgumentNullException;

class COipSocialStoreCartButton extends \COipSocialStoreCart {

    public function initParams($arParams)
    {
        try {
            $arParams = parent::initParams($arParams);

            if (!is_set($arParams["PRODUCT_ID"])) {
                throw new ArgumentNullException("PRODUCT_ID");
            }

            $this->setDefaultParam($arParams["BUTTON_TEXT_ADD"], "Добавить в корзину");
            $this->setDefaultParam($arParams["BUTTON_TEXT_REMOVE"], "Удалить из корзины");
            $this->setDefaultParam($arParams["BUTTON_ICON_ADD"], "");
            $this->setDefaultParam($arParams["BUTTON_ICON_REMOVE"], "");
        }
        catch (ArgumentNullException $exception) {
            $this->arResult["EXCEPTION"] = $exception->getMessage();
        }

        return $arParams;
    }

    public function executeComponent()
    {

       $cart = $this->getProcessorResult();

        if($cart) {
            $this->arResult["CART"] = $cart;
            $this->arResult["IN_CART"] = ($cart->hasProduct($this->getParam("PRODUCT_ID")));
        }

        $this->includeComponentTemplate();
    }
}