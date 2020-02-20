<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

\CBitrixComponent::includeComponentClass("oip:relevant.products.likes.widget");

class CRelevantProductsViewsCategoryCount extends \CRelevantProductsLikeWidget
{
    protected function initParams($arParams) {
        return parent::initParams($arParams, "SECTION_ID");
    }

    public function execute()
    {
        $this->arResult["VIEWS"] = $this->dw->getSectionViewsCount($this->getParam("SECTION_ID"));
    }
}