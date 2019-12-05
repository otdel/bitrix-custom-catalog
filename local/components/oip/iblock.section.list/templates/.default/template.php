<?php

use Oip\Custom\Component\Iblock\Section;

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

/** @var $this \CBitrixComponentTemplate */
/** @var $component \COipIblockSectionList */
$component = $this->getComponent();

/** @var array $arResult */
/** @var Section[] $sections */
$sections = $arResult["SECTIONS"];

?>

<?
// Если это деталка (запрошен котнкретный раздел, без дочерних) - выводим шаблон деталки
if ($component->isSingleSection()) {
    $section = $arResult["SECTIONS"][0];
    if($section) {
        include_once(__DIR__ . "/include/sectionDetail.php");
    }
}
// Иначе - выводим шаблон списка разделов
else {
    include_once(__DIR__ . "/include/sectionList.php");
}
?>


