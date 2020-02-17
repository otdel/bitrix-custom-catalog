<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/** @var $em Bitrix\Main\EventManager */

global $APPLICATION;

$em->addEventHandler("main", "OnPageStart", function () use ($APPLICATION)  {
    $APPLICATION->IncludeComponent("oip:guest.user.processor.init",
        "", []);
});

$em->addEventHandler("main", "OnProlog", function () use ($APPLICATION) {
    $APPLICATION->IncludeComponent("oip:social.store.cart.processor", "", []);
});

$em->addEventHandler("main", "OnProlog", function() use ($APPLICATION) {
    $APPLICATION->IncludeComponent("oip:relevant.products.likes.processor", "", []);
});