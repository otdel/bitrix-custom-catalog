<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Oip\RelevantProducts\DataWrapper;
use Oip\RelevantProducts\DBDataSource;
use Oip\CacheInfo;

\CBitrixComponent::includeComponentClass("oip:component");

class CRelevantProductsLikesUserCount extends \COipComponent
{
    /** @var Oip\RelevantProducts\DataWrapper $dw */
    private $dw;

    public function __construct(?CBitrixComponent $component = null)
    {
        parent::__construct($component);

        global $DB;
        $cacheInfo = new CacheInfo();
        $ds = new DBDataSource($DB, $cacheInfo);
        $this->dw = new DataWrapper($ds);
    }

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

    /** @return int */
    private function getUserId() {
        global $USER;
        /** @var Oip\GuestUser\Handler $OipGuestUser  */
        global $OipGuestUser;

        return ($USER->IsAuthorized()) ? (int)$USER->GetID() : $OipGuestUser->getUser()->getNegativeId();

    }
}