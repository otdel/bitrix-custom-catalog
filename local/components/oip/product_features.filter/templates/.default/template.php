<?php

use Oip\ProductFeature\SectionFeatureOption;

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arResult */
/** @var array $arParams */

// Вывод ошибок, если они были
if (isset($arResult["ERRORS"])) { ?>
    <h3>Возникли ошибки:</h3>
    <ul>
        <? foreach ($arResult["ERRORS"] as $error) { ?>
            <li><?= $error ?></li>
        <? } ?>
    </ul>
    <? return;
}

/** @var array $filterSectionFeatureValues */
$filterSectionFeatureValues = $arResult["filterSectionFeatureValues"];
/** @var SectionFeatureOption[] $sectionFeatureOptions */
$sectionFeatureOptions = $arResult["sectionFeatureOptions"];

include(__DIR__ . "/include/form.php");

?>

