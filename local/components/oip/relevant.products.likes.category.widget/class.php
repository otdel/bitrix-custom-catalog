<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\ArgumentException;
use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\ArgumentTypeException;

class CRelevantProductsLikesCategoryWidget extends \CRelevantProducts
{
    protected function initParams($arParams) {

        $arParams = parent::initParams($arParams);

        try {
            if(!is_set($arParams["SECTION_ID"])) {
                throw new ArgumentNullException("SECTION_ID");
            }

            if(!intval($arParams["SECTION_ID"])) {
                throw new ArgumentTypeException("SECTION_ID");
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
                $arSections = $this->dw->getSubsectionsId($this->getParam("SECTION_ID"));
                $arSections[] = $this->getParam("SECTION_ID");
                $this->arResult["LIKES"] = $this->dw->getSectionLikesCount($arSections);
                $this->arResult["IS_LIKED"] = $this->dw->isSectionLikedByUser($this->getParam("SECTION_ID"), $this->getUserId());
            }
            catch(Exception $e) {
                $this->arResult["EXCEPTION"] = $e;
            }
        }

        $this->includeComponentTemplate();
    }
}