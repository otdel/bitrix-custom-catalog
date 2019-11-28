<?php

use Oip\RelevantProducts\RelevantSection;
use Oip\RelevantProducts\RelevantProduct;

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arResult */

// Получаем фильтр, по которому выводятся данные
$filter = (!isset($arResult["FILTER"])) ? "FILTER_TOP_CATEGORIES" : $arResult["FILTER"];

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

// Для отладки - название фильтра
echo "<h1>Фильтр: " . $arResult["FILTER"] . "</h1>";

// В зависимости от выбранного фильтра, обрабатываем объекты
switch ($filter) {
    // Список актуальных для пользователя товаров
    case "FILTER_RELEVANT_PRODUCTS":
        /** @var RelevantSection[] $relevantSections */
        $relevantSections = $arResult["TOP_SECTIONS"];
        foreach ($relevantSections as $section) { ?>
            <h3>Категория <?=$section->getId()?></h3>
            <p>Вес (сила интереса): <?=$section->getWeight();?> (Просмотров: <?=$section->getViewsCount();?> , лайков:  <?=$section->getLikesCount();?>)</p>
            <ol>
                <? foreach ($section->getRelevantProducts() as $relevantProduct) { ?>
                <li><?=$relevantProduct->getName() . ($relevantProduct->getViewsCount() == 0 ? " (Не просмотрен)" : "Просмотрен")?></li>
                <? } ?>
            </ol>
        <? }
        break;

    // Список популярных у пользователя категорий
    case "FILTER_TOP_SECTIONS":
        $viewedSections = $arResult["TOP_SECTIONS"];
        include(__DIR__ . "/include/topSectionsList.php");
        break;

    // Список товаров в топ категории
    case "FILTER_TOP_SECTION_PRODUCTS":
        $topSection = $arResult["TOP_SECTION"];
        include(__DIR__ . "/include/topSectionProducts.php");
        break;

    // Полный список просмотренных товаров с фильтрацией по категориям
    case "FILTER_FULL_PRODUCTS_LIST":
        $viewedSections = $arResult["FULL_PRODUCT_LIST"];
        // Фильтр по категориям (перечисление id категорий);
        if (isset($arParams["FILTER_SECTIONS"]))  $filterSections = $arParams["FILTER_CATEGORIES"];
        include(__DIR__ . "/include/fullProductsList.php");
        break;

    // Список лайкнутых товаров
    case "FILTER_LIKED_PRODUCTS":
        $likedSections = $arResult["LIKED_PRODUCTS"];
        include(__DIR__ . "/include/likedProductsList.php");
        break;

    // Самые просматриваемые категории
    case "FILTER_MOST_VIEWED_SECTIONS_LIST":
        $mostViewedSections = $arResult["MOST_VIEWED_SECTIONS_LIST"];
        include(__DIR__ . "/include/mostViewedSectionsList.php");
        break;

    // Самые залайканные категории
    case "FILTER_MOST_LIKED_SECTIONS_LIST":
        $mostLikedSections= $arResult["MOST_LIKED_SECTIONS_LIST"];
        include(__DIR__ . "/include/mostLikedSectionsList.php");
        break;

    // Самые просматриваемые товары
    case "FILTER_MOST_VIEWED_PRODUCTS_LIST":
        $mostLikedProducts = $arResult["MOST_VIEWED_PRODUCTS_LIST"];
        include(__DIR__ . "/include/mostViewedProductsList.php");
        break;

    // Самые залайканные товары
    case "FILTER_MOST_LIKED_PRODUCTS_LIST":
        $mostLikedProducts = $arResult["MOST_LIKED_PRODUCTS_LIST"];
        include(__DIR__ . "/include/mostLikedProductsList.php");
        break;

    // Новые товары из топ-10 категорий
    case "FILTER_NEW_PRODUCTS_LIST":
        $newProductsSections = $arResult["NEW_PRODUCTS_LIST"];
        include(__DIR__ . "/include/newProductsList.php");
        break;
}


