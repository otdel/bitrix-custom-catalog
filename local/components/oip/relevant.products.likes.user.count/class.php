<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

\CBitrixComponent::includeComponentClass("oip:relevant.products");

class CRelevantProductsLikesUserCount extends \CRelevantProducts
{
    public function executeComponent()
    {
        try {
            $userId = $this->getUserId();
            $this->arResult["LIKES"] = $this->dw->getUserLikes($userId);
        }
        catch (Exception $e) {
            $this->arResult["EXCEPTION"] = $e;
        }

        $this->includeComponentTemplate();
    }
}