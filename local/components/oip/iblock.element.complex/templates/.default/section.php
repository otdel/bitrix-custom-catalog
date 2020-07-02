<?php
/** @var $this \CBitrixComponentTemplate */
/** @var $component \COipIblockElementComplex */
/** @var array $arResult */
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$sectionId = (int)$arResult["VARIABLES"]["SECTION_ID"] ?? null;
$sectionCode = $arResult["VARIABLES"]["SECTION_CODE"] ?? null;

$section = ($sectionCode) ? $sectionCode : $sectionId;

$component->setParam("SECTION_CODE", $sectionCode);
$component->setParam("SECTION_ID", $sectionId);
?>

<?$APPLICATION->IncludeComponent("oip:iblock.element.page","", $component->getParams())?>

