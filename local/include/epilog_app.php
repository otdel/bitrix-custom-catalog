<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/** @var $em Bitrix\Main\EventManager */

$em->addEventHandler("main", "OnEpilog", function () {
    global $APPLICATION;
    $APPLICATION->IncludeComponent("oip:guest.user.processor.write","",[]);
});