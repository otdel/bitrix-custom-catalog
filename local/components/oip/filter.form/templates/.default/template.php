<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var $this \CBitrixComponentTemplate */
/** @var $component \COipFilterForm */
$filterId = $component->getParam("FILTER_ID");
?>

<?$arFilterTemplate = $APPLICATION->IncludeComponent("oip:filter.processor","",[
    "FILTER_ID" => $filterId,
    "MODE" => "TEMPLATE"
])?>

<?
$namePlaceholder = ($arFilterTemplate["f{$filterId}_fNAME"])
    ? $arFilterTemplate["f{$filterId}_fNAME"][0] : "";
?>
<div class="uk-section uk-section-xsmall uk-section-secondary">
    <div class="uk-container uk-container-large">
        <form class="uk-flex-middle uk-flex-center uk-grid-small oip-filter-form" uk-grid>
            <div class="uk-width-auto">
                <div class="uk-panel uk-display-inline-block">
                    <div class="uk-h3 uk-display-inline-block">Я ищу</div>
                </div>
            </div>
            <div class="uk-width-1-5@m">
                <div class="uk-panel">
                    <input class="uk-input oip-filter-input oip-filter-simple-item" type="text" name="f<?=$filterId?>_fNAME"
                           placeholder="Впишите сюда название товара" value="<?=$namePlaceholder?>"
                           data-filter-id="<?=$filterId?>">
                </div>
            </div>
            <div class="uk-width-1-5@m">
                <div class="uk-panel">
                    <?$APPLICATION->IncludeComponent("oip:iblock.section.list","select-tree",[
                        "IBLOCK_ID" => 29,
                        "DEPTH" => 2,
                        "FILTER_ID" => $component->getParam("FILTER_ID"),
                        "FILTER_PARAMS" => $arFilterTemplate
                    ])?>
                </div>
            </div>

            <?if($component->getParam("BRANDS_IBLOCK_ID")):?>
                <?$APPLICATION->IncludeComponent(
                    "oip:iblock.element.list","brands-in-filter-form",
                    [
                        "IBLOCK_ID" => $component->getParam("BRANDS_IBLOCK_ID"),
                        "SHOW_ALL" => "Y",
                        "FILTER_ID" => $filterId,
                        "FILTER_PARAMS" => $arFilterTemplate
                    ]
                )?>
            <?endif?>
            <div class="uk-width-auto">
                <div class="uk-panel uk-display-inline-block">
                    <button type="button" class="uk-button uk-button-primary" id="oip-filter-apply" data-filter-id="<?=$filterId?>">Найти</button>
                </div>
            </div>

            <?if(!empty($arFilterTemplate)):?>
                <i class="uk-icon-button uk-margin-small-left" uk-icon="close" id="oip-filter-reset"
                   data-filter-id="<?=$filterId?>"></i>
            <?endif?>

        </form>
    </div>
</div>
