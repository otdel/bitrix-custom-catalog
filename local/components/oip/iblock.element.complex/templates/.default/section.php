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

<?$APPLICATION->IncludeComponent("oip:iblock.section.list","",[
    "IBLOCK_ID" => $component->getParam("IBLOCK_ID"),
    "BASE_SECTION" => $section,
    "CACHE" => $component->getParam("IS_CACHE")
])?>

<?$returnedSectionData = $APPLICATION->IncludeComponent("oip:iblock.section.list","",[
    "IBLOCK_ID" => $component->getParam("IBLOCK_ID"),
    "BASE_SECTION" => $section,
    "DEPTH" => 0,
    "USER_FIELDS" => array("UF_*"),
    "CACHE" => $component->getParam("IS_CACHE")
])?>

<?
    $component->rewriteComponentParams("LIST_VIEW_TITLE_TEXT", $returnedSectionData["SECTION_NAME"]);
    $component->rewriteComponentParams("COUNT", (int)$returnedSectionData["UF_ELEMENTS_NUMBER"]);
    $component->rewriteComponentParams("LIST_VIEW_CONTAINER_ELEMENT_WIDTH_CSS",
        $returnedSectionData["UF_COLUMNS_COUNT"]);
    $component->rewriteComponentParams("LIST_VIEW_TITLE_CSS",
        $returnedSectionData["UF_ELEMENT_TITLE_CSS"]);
    $component->rewriteComponentParams("SHOW_SIDEBAR",
        $returnedSectionData["UF_SIDEBAR_LIST"], true);
    $component->rewriteComponentParams("SIDEBAR_WIDTH", $returnedSectionData["UF_SIDEBAR_WIDTH"]);
?>

<?$APPLICATION->IncludeComponent("oip:iblock.element.page","", $component->getParams())?>

