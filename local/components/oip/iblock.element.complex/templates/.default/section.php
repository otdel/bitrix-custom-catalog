<?php
/** @var $this \CBitrixComponentTemplate */
/** @var $component \COipIblockElementComplex */
/** @var array $arResult */
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$sectionId = (int)$arResult["VARIABLES"]["SECTION_ID"];
$sectionCode = $arResult["VARIABLES"]["SECTION_CODE"];
?>

<?$APPLICATION->IncludeComponent("oip:iblock.element.page","",
    array_merge($component->getParams(),["SECTION_ID" => $sectionId, "SECTION_CODE" => $sectionCode])
    )?>

