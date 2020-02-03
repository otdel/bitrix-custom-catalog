<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/** @var $em Bitrix\Main\EventManager */

$em->addEventHandler("main", "OnPageStart", function () {
    global $APPLICATION;
    $APPLICATION->IncludeComponent("oip:guest.user.processor.init",
        "", []);
});

$em->addEventHandler("main", "OnProlog", function () {
    global $APPLICATION;
    $APPLICATION->IncludeComponent("oip:social.store.cart.processor", "", []);
});