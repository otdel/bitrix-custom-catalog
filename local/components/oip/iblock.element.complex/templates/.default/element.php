<?php
/** @var $this \CBitrixComponentTemplate */
/** @var $component \COipIblockElementComplex */
/** @var array $arResult */
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$elementId = (int)$arResult["VARIABLES"]["ELEMENT_ID"];
?>

<?$APPLICATION->IncludeComponent("oip:iblock.element.one","",
    array_merge($component->getParams(),["ELEMENT_ID" => $elementId])
)?>