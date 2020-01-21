<?php

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

// Если были запрошены характеристики по нескольким товарам, используем один шаблон
if (count($arParams["ELEMENT_ID"]) > 1) {
    include(__DIR__ . "/include/elementList.php");
}
// Если же запрашивались характеристики одного товара - используем другой шаблон
else if (count($arParams["ELEMENT_ID"]) == 1) {
    include(__DIR__ . "/include/elementOne.php");
}

