<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

\CBitrixComponent::includeComponentClass("oip:social.store.cart");

use Oip\SocialStore\Cart\Handler as Cart;

class COipSocialStoreCartWidget extends \COipSocialStoreCart {
    public function executeComponent()
    {
        $cart = $this->getProcessorResult();

        if($cart) {
            $this->arResult["COUNT"] = $cart->getProducts()->count();
        }

        $this->includeComponentTemplate();
    }
}