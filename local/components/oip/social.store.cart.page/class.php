<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

\CBitrixComponent::includeComponentClass("oip:social.store.cart");

class COipSocialStoreCartPage extends \COipSocialStoreCart {

    public function executeComponent()
    {
        $this->arResult["CART"] = $this->getProcessorResult();

        if(!$this->arResult["EXCEPTION"]) {
            $this->arResult["ERRORS"] = $this->getOrderCreatingErrors();
            $this->arResult["EXCEPTION"] = $this->getOrderCreatingException();
            $this->arResult["SUCCESS"] = $this->getOrderCreatingSuccess();
        }

        $this->includeComponentTemplate();
    }

    private function getOrderCreatingSuccess() {
        global $OipSocialStoreCartOrderCreatedSuccess;
        return $OipSocialStoreCartOrderCreatedSuccess;
    }

    private function getOrderCreatingErrors() {
        global $OipSocialStoreCartOrderCreatingErrors;
        return $OipSocialStoreCartOrderCreatingErrors;
    }
    private function getOrderCreatingException() {
        global $OipSocialStoreCartOrderCreatingErrorException;
        return $OipSocialStoreCartOrderCreatingErrorException;
    }
}