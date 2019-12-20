<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

\CBitrixComponent::includeComponentClass("oip:social.store.cart");

use Bitrix\Main\Application;

use Oip\SocialStore\Cart\Handler as Cart;

class COipSocialStoreCartPage extends \COipSocialStoreCart {

    public function executeComponent()
    {
        $cart = parent::executeComponent();

        $this->arResult["CART"] = $cart;
        $this->includeComponentTemplate();
    }
}