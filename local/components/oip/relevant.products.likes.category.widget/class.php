<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

\CBitrixComponent::includeComponentClass("oip:relevant.products.likes.widget");

class CRelevantProductsLikesCategoryWidget extends \CRelevantProductsLikeWidget
{
    protected function initParams($arParams) {
        return parent::initParams($arParams, "SECTION_ID");
    }

    public function execute()
    {
        $arSections = $this->dw->getSubsectionsId($this->getParam("SECTION_ID"));
        $arSections[] = $this->getParam("SECTION_ID");
        $this->arResult["LIKES"] = $this->dw->getSectionLikesCount($arSections);
        $this->arResult["IS_LIKED"] = $this->dw->isSectionLikedByUser($this->getParam("SECTION_ID"), $this->getUserId());
    }
}