<?php
/** @var $this \CBitrixComponentTemplate */
/** @var $component \COipIblockElementComplex */
/** @var array $arResult */
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$elementId = (int)$arResult["VARIABLES"]["ELEMENT_ID"];
$elementCode = $arResult["VARIABLES"]["ELEMENT_CODE"];
?>

<?$APPLICATION->IncludeComponent("oip:iblock.element.one","",
    array_merge($component->getParams(),["ELEMENT_CODE" => $elementCode,"ELEMENT_ID" => $elementId])
)?>