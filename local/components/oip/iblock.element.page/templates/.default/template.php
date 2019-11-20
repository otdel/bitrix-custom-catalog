<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/** @var $this \CBitrixComponentTemplate */
/** @var $component \COipIblockElementPage */
/** @var $returnedData \Oip\Custom\Component\Iblock\ReturnedData */
$component = $this->getComponent();
?>

<div class="uk-section uk-section-<?=$component->getParam("LIST_VIEW_WRAP_COLOR")?>
            uk-section-<?=$component->getParam("LIST_VIEW_WRAP_SIZE")?>
            <?=$component->getParam("LIST_VIEW_WRAP_ADD_CSS")?>">
    <div class="uk-container uk-container-<?=$component->getParam("LIST_VIEW_CONTAINER_WIDTH_CSS")?>">
        <div class="uk-grid-large" uk-grid>

            <div class="uk-width-expand@m">

                <?if($component->getParam("LIST_VIEW_TITLE_TEXT")):?>

                <<?=$component->getParam("LIST_VIEW_TITLE_TAG")?>
                    class="<?=$component->getParam("LIST_VIEW_TITLE_CSS")?>
                    <?=$component->getParam("LIST_VIEW_TITLE_ALIGN")?>">
                    <span class="icon icon-<?=$component->getParam("LIST_VIEW_TITLE_ICON_CSS")?>"></span>
                    <?=$component->getParam("LIST_VIEW_TITLE_TEXT")?>
                </<?=$component->getParam("LIST_VIEW_TITLE_TAG")?>>

                <?endif?>

                <?include_once (__DIR__."/include/top.php")?>

                <?$returnedData = $APPLICATION->IncludeComponent("oip:iblock.element.list","",
                    $component->getParams(), $component);?>

                <?$pagination = $returnedData->getPagination()?>

                <?if(!empty($pagination) && $pagination["PAGES"] > 1):?>
                    <?$APPLICATION->IncludeComponent("oip:page.navigation","",[
                        "NAV_ID" => $pagination["NAV_ID"],
                        "PAGES" => $pagination["PAGES"],
                        "PAGE" => $pagination["PAGE"],
                    ])?>
                <?endif?>

            </div>

            <?if($component->isParam("SHOW_SIDEBAR")):?>
                <div class="uk-width-1-5@m">
                    <?include_once (__DIR__."/include/sidebar.php")?>
                </div>
            <?endif?>

        </div>
    </div>
</div>


