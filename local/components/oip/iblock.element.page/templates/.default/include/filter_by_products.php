<?php
/** @var $component \COipIblockElementPage */

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$sectionId = $component->getParam("SECTION_ID");
$sectionCode = $component->getParam("SECTION_CODE");

$brandsFilter = [];
$tagsFilter = [];

if($component->getParam("BRANDS_IBLOCK_ID") || $component->getParam("TAGS_IBLOCK_ID")) {

    $arFilter = ["IBLOCK_ID" => $component->getParam("IBLOCK_ID")];

    if($sectionId) {
        $arFilter["SECTION_ID"] = $sectionId;
        $arFilter["INCLUDE_SUBSECTIONS"] = "Y";
    }

    if($sectionCode) {
        $arFilter["SECTION_CODE"] = $sectionCode;
        $arFilter["INCLUDE_SUBSECTIONS"] = "Y";
    }

    $info = [];
    $dbInfo = CIBlockElement::GetList([], $arFilter,
        false, false, ["ID", "IBLOCK_ID", "NAME", "PROPERTY_BRANDS", "PROPERTY_TAGS", "IBLOCK_SECTION_ID"]);
    while ($inf = $dbInfo->GetNext()) {
        $info[] = $inf;
    }


    if ($component->getParam("BRANDS_IBLOCK_ID")) {
        foreach($info as $brand) {
            if($brand["PROPERTY_BRANDS_VALUE"]) {
                $brandsFilter[$brand["PROPERTY_BRANDS_VALUE"]] = $brand["PROPERTY_BRANDS_VALUE"];
            }
        }
    }

    if ($component->getParam("TAGS_IBLOCK_ID")) {
        foreach($info as $tag) {
            if($tag["PROPERTY_TAGS_VALUE"]) {
                $tagsFilter[$tag["PROPERTY_TAGS_VALUE"]] = $tag["PROPERTY_TAGS_VALUE"];
            }
        }
    }
}