<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

\CBitrixComponent::includeComponentClass("oip:social.store.cart");

class COipSocialStoreCartPage extends \COipSocialStoreCart {

    public function executeComponent()
    {
        $this->arResult["CART"] = $this->getProcessorResult();

        if(!$this->arResult["EXCEPTION"]) {
            $this->arResult["EXCEPTION"] = $this->getOrderCreatingError();
            $this->arResult["SUCCESS"] = $this->getOrderCreatingSuccess();
        }

        $this->includeComponentTemplate();
    }

    private function getOrderCreatingSuccess() {
        global $OipSocialStoreCartOrderCreatedSuccess;
        return $OipSocialStoreCartOrderCreatedSuccess;
    }

    private function getOrderCreatingError() {
        global $OipSocialStoreCartOrderCreatingErrorException;
        return $OipSocialStoreCartOrderCreatingErrorException;
    }
}