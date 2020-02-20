<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Oip\RelevantProducts\DataWrapper;
use Oip\RelevantProducts\DBDataSource;
use Oip\CacheInfo;
use Oip\Util\Cache\BXCacheService;

\CBitrixComponent::includeComponentClass("oip:component");

abstract class CRelevantProducts extends \COipComponent
{
    /** @var Oip\RelevantProducts\DataWrapper $dw */
    protected $dw;

    public function __construct(?CBitrixComponent $component = null)
    {
        parent::__construct($component);

        global $DB;
        $cacheInfo = new CacheInfo();
        $cacheService = new BXCacheService();
        $ds = new DBDataSource($DB, $cacheInfo, $cacheService);
        $this->dw = new DataWrapper($ds);
    }

    /** @return int */
    protected function getUserId() {
        global $USER;
        /** @var Oip\GuestUser\Handler $OipGuestUser  */
        global $OipGuestUser;

        return ($USER->IsAuthorized()) ? (int)$USER->GetID() : $OipGuestUser->getUser()->getNegativeId();

    }
}