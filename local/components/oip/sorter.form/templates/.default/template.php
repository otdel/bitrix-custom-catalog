<?php

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/** @var array $arResult */
/** @var array $arParams */

?>

<?if($arResult["EXCEPTION"]):?>

    <?$APPLICATION->IncludeComponent("oip:system.exception.view","", [
        "EXCEPTION" => $arResult["EXCEPTION"]
    ]);?>

<?else:?>

    <?php
        $filterId = $arParams["FILTER_ID"];
        $recommendSort = $arResult["RECOMMEND_SORT"];
        $ratingSort = $arResult["RATING_SORT"];
        $dateSort = $arResult["DATE_SORT"];
        $activeSortLabel = $arResult["ACTIVE_SORT_LABEL"];
    ?>

    <input type="hidden" name="data-filter-id" id="sort-filter-id" value="<?=$filterId?>">

    <div class="uk-inline">
        <button class="uk-button uk-button-default uk-button-small uk-text-lowercase" type="button">
            <i class="uk-margin-small-right" uk-icon="settings"></i>
            <?=$activeSortLabel?>
        </button>

        <div uk-dropdown="mode: click">
            <ul class="uk-nav uk-dropdown-nav">
                <li class="<?if(!$recommendSort && !$ratingSort && !$dateSort):?>uk-active<?endif?>">
                    <a href="javascript:void(0);" id="oip-filter-sort-reset">По умолчанию</a>
                </li>

                <li class="<?if($recommendSort):?>uk-active<?endif?>">
                    <a href="javascript:void(0);" data-sort-name="Recommend" class="oip-filter-sort-item">По рекомендациям</a>
                </li>

                <li class="<?if($ratingSort):?>uk-active<?endif?>">
                    <a href="javascript:void(0);" data-sort-name="Rating" class="oip-filter-sort-item">По популярности</a>
                </li>

                <li class="<?if($dateSort):?>uk-active<?endif?>">
                    <a href="javascript:void(0);" data-sort-name="created" class="oip-filter-sort-item">Новые первыми</a>
                </li>
            </ul>
        </div>
    </div>

<?endif?>


