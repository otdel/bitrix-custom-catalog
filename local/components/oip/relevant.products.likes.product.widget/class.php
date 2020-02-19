<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

\CBitrixComponent::includeComponentClass("oip:relevant.products.likes.widget");

class CRelevantProductsLikesProductWidget extends \CRelevantProductsLikeWidget
{
    protected function initParams($arParams) {
        return parent::initParams($arParams, "PRODUCT_ID");
    }

    public function execute()
    {
        $userId = $this->getUserId();
        $productId = $this->getParam("PRODUCT_ID");

        $this->arResult["LIKES"] = $this->dw->getProductLikes($productId);
        $this->arResult["IS_LIKED"] = $this->dw->isProductLikedByUser($productId, $userId);
    }
}