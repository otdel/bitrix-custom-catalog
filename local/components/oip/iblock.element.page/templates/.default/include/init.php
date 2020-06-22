<?php

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/** @var $this \CBitrixComponentTemplate */
/** @var $component \COipIblockElementPage */
/** @var $returnedData \Oip\Custom\Component\Iblock\ReturnedData */
$component = $this->getComponent();
$filterId = $component->getComponentId();

$arrFilter = $APPLICATION->IncludeComponent("oip:filter.processor","",[
    "FILTER_ID" => $filterId,
]);

$arrSort = array_filter($arrFilter, function ($key) {
    return ($key == "SORT_1" || $key == "BY_1");
},ARRAY_FILTER_USE_KEY);

$arrFilterPure = array_filter($arrFilter, function ($key) {
    return ($key != "SORT_1" && $key != "BY_1");
},ARRAY_FILTER_USE_KEY);

if($arrSort) {
    $component->setParam("SORT_1", $arrSort["SORT_1"]);
    $component->setParam("BY_1", $arrSort["BY_1"]);
    $component->setParam("SORT_2", "SORT");
    $component->setParam("BY_2", "ASC");
}

$arFilterTemplate = $APPLICATION->IncludeComponent("oip:filter.processor","",[
    "FILTER_ID" => $filterId,
    "MODE" => "TEMPLATE"
]);