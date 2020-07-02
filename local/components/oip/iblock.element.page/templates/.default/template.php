<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

include_once (__DIR__."/include/init.php");
include_once (__DIR__."/include/filter_by_products.php");

/** @var $this \CBitrixComponentTemplate */
/** @var $component \COipIblockElementPage */
/** @var $returnedData \Oip\Custom\Component\Iblock\ReturnedData */
/** @var $arrSort array */
/** @var $arrFilterPure array */
/** @var $arFilterTemplate array */
/** @var $brandsFilter array */

?>

<?$APPLICATION->IncludeComponent("oip:filter.form","", [
    "FILTER_ID" => $component->getComponentId(),
    "IBLOCK_ID" => $component->getParam("IBLOCK_ID"),
    "BRANDS_IBLOCK_ID" => $component->getParam("BRANDS_IBLOCK_ID"),
    "SECTION" => ($component->getParam("SECTION_CODE"))
        ? $component->getParam("SECTION_CODE") : $component->getParam("SECTION_ID"),
    "BRANDS_FILTER" => $brandsFilter
]);?>

<?include_once (__DIR__."/include/section.php")?>

<style>
    #oip-ajax-container {
        position: relative;
    }

    #oip-ajax-loader {
        position: absolute;
        width: 100%;
        height: 100%;
        background: #fff;
        z-index: -1;
        opacity: .95;
        display: flex;
        justify-content: center;
    }

    #oip-ajax-loader.active {
        z-index: 9999;
    }

    .oip-ajax-loader-spinner {
        margin-top: 30px;
    }
</style>

<div class="uk-section uk-section-<?=$component->getParam("LIST_VIEW_WRAP_COLOR")?>
            uk-section-<?=$component->getParam("LIST_VIEW_WRAP_SIZE")?>
            <?=$component->getParam("LIST_VIEW_WRAP_ADD_CSS")?>">
    <div class="uk-container uk-container-<?=$component->getParam("LIST_VIEW_CONTAINER_WIDTH_CSS")?>">
        <div class="uk-grid-large" uk-grid>

            <div class="uk-width-expand@m">

                <div id="oip-ajax-container">
                    <div id="oip-ajax-loader"><div uk-spinner="ratio:2.5" class="oip-ajax-loader-spinner"></div></div>
                    <div id="oip-ajax-inner">

                        <?if($component->getParam("LIST_VIEW_TITLE_TEXT")):?>

                            <<?=$component->getParam("LIST_VIEW_TITLE_TAG")?>
                            class="<?=$component->getParam("LIST_VIEW_TITLE_CSS")?>
                            <?=$component->getParam("LIST_VIEW_TITLE_ALIGN")?>">
                            <span class="icon icon-<?=$component->getParam("LIST_VIEW_TITLE_ICON_CSS")?>"></span>
                            <?=$component->getParam("LIST_VIEW_TITLE_TEXT")?>
                        </<?=$component->getParam("LIST_VIEW_TITLE_TAG")?>>

                        <?endif?>

                    <?include_once (__DIR__."/include/top.php")?>
                    <?
                    $componentParams = array_merge($component->getParams(),["FILTER" => $arrFilterPure]);
                    ?>

                    <?$returnedData = $APPLICATION->IncludeComponent("oip:iblock.element.list","",
                        $componentParams);?>

                    <?$pagination = $returnedData->getPagination()?>

                    <?if(!empty($pagination) && $pagination["PAGES"] > 1):?>
                        <?$APPLICATION->IncludeComponent("oip:page.navigation","",[
                            "NAV_ID" => $pagination["NAV_ID"],
                            "PAGES" => $pagination["PAGES"],
                            "PAGE" => $pagination["PAGE"],
                        ])?>
                    <?endif?>

                    </div>
                </div>

            </div>

            <?if($component->isParam("SHOW_SIDEBAR")):?>
                <div class="uk-width-1-5@m">
                    <?include_once (__DIR__."/include/sidebar.php")?>
                </div>
            <?endif?>

        </div>
    </div>
</div>


