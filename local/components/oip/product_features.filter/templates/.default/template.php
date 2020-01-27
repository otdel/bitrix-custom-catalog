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

// Если был передан набор данных для фильтрации
if (isset($_POST["filter"]["action"]) && ($_POST["filter"]["action"] == "doFilter")) {
    include(__DIR__ . "/include/processFilter.php");
}
// Иначе - просто показываем форму с фильтрами
else {
    include(__DIR__ . "/include/form.php");
}

?>

