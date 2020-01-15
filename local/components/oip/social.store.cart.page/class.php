<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

\CBitrixComponent::includeComponentClass("oip:social.store.cart");


class COipSocialStoreCartPage extends \COipSocialStoreCart {

    public function executeComponent()
    {
        $cart = parent::executeComponent();

        $this->arResult["CART"] = $cart;
        $this->arResult["EXCEPTION"] = $this->exception;
        $this->arResult["SUCCESS"] = $this->success;

        $this->includeComponentTemplate();
    }
}