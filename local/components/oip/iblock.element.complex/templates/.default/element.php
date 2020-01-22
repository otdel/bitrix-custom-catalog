<?php
/** @var $this \CBitrixComponentTemplate */
/** @var $component \COipIblockElementComplex */
/** @var array $arResult */
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$elementId = (int)$arResult["VARIABLES"]["ELEMENT_ID"];
$elementCode = $arResult["VARIABLES"]["ELEMENT_CODE"];

$component->setParam("ELEMENT_CODE", $elementCode);
$component->setParam("ELEMENT_ID", $elementId);
?>

<?$APPLICATION->IncludeComponent("oip:iblock.element.one","", $component->getParams())?>