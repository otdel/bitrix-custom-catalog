<?php

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$brandsFilter = [];
$tagsFilter = [];

if($component->getParam("BRANDS_IBLOCK_ID") || $component->getParam("TAGS_IBLOCK_ID")) {

    $info = [];
    $dbInfo = CIBlockElement::GetList([], ["IBLOCK_ID" => $component->getParam("IBLOCK_ID")],
        false, false, ["ID", "IBLOCK_ID", "PROPERTY_BRANDS", "PROPERTY_TAGS"]);
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
