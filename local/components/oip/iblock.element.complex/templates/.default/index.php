<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/** @var $this \CBitrixComponentTemplate */
/** @var $component \COipIblockElementComplex */
?>

<?$APPLICATION->IncludeComponent("oip:iblock.section.list","",[
    "IBLOCK_ID" => $component->getParam("IBLOCK_ID"),
    "CACHE" => $component->getParam("IS_CACHE")
])?>

<?$APPLICATION->IncludeComponent("oip:iblock.element.page","",$component->getParams())?>

