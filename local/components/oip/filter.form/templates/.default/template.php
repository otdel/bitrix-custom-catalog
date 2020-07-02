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

<style>
    .oip-ajax-form-loader-container {
        position: relative;
    }

    #oip-ajax-form-loader {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: #6f6f6f;
        z-index: -1;
        opacity: .75;
        display: flex;
        justify-content: center;
    }

    #oip-ajax-form-loader.active {
        z-index: 9999;
    }

    .oip-ajax-loader-spinner {
        margin-top: 30px;
    }
</style>

<div class="uk-section uk-section-xsmall uk-section-secondary oip-ajax-form-loader-container">

    <div id="oip-ajax-form-loader"><div uk-spinner="ratio:2.5" class="oip-ajax-loader-spinner"></div></div>

    <div class="uk-container uk-container-large">

        <div id="oip-ajax-filter-form-container">
            <div id="oip-ajax-filter-form-inner">

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
                                "IBLOCK_ID" => $component->getParam("IBLOCK_ID"),
                                "DEPTH" => 2,
                                "FILTER_ID" => $component->getParam("FILTER_ID"),
                                "FILTER_PARAMS" => $arFilterTemplate,
                                "BASE_SECTION" => $component->getParam("SECTION")
                            ])?>
                        </div>
                    </div>

                    <?if($component->getParam("BRANDS_IBLOCK_ID") && $component->getParam("BRANDS_FILTER")):?>
                        <?$APPLICATION->IncludeComponent(
                            "oip:iblock.element.list","brands-in-filter-form",
                            [
                                "IBLOCK_ID" => $component->getParam("BRANDS_IBLOCK_ID"),
                                "SHOW_ALL" => "Y",
                                "FILTER_ID" => $filterId,
                                "FILTER_PARAMS" => $arFilterTemplate,
                                "FILTER" => ["ID" => $component->getParam("BRANDS_FILTER")]
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
    </div>
</div>
