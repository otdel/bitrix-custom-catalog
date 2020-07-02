<?php
/** @var $component \COipIblockElementPage */
/** @var $returnedData \Oip\Custom\Component\Iblock\ReturnedData */

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if($component->getParam("SECTION_CODE") || $component->getParam("SECTION_ID")):?>

    <?$section = ($component->getParam("SECTION_CODE"))
        ? $component->getParam("SECTION_CODE") : $component->getParam("SECTION_ID");?>

    <?$APPLICATION->IncludeComponent("oip:iblock.section.list","",[
        "IBLOCK_ID" => $component->getParam("IBLOCK_ID"),
        "BASE_SECTION" => $section,
        "CACHE" => $component->getParam("IS_CACHE")
    ])?>

    <?$returnedSectionData = $APPLICATION->IncludeComponent("oip:iblock.section.list","",[
        "IBLOCK_ID" => $component->getParam("IBLOCK_ID"),
        "BASE_SECTION" => $section,
        "DEPTH" => 0,
        "USER_FIELDS" => array("UF_*"),
        "CACHE" => $component->getParam("IS_CACHE")
    ])?>

    <?
    // если вдруг при вызвове комплексного был задан кастомный заголовок
    // то его нужно сбросить при выводе данных в разделе - тут приоритетнее название раздела
    $component->setParam("LIST_VIEW_TITLE_TEXT", "");
    $component->rewriteComponentParams("LIST_VIEW_TITLE_TEXT", $returnedSectionData["SECTION_NAME"]);
    $component->rewriteComponentParams("SECTION_NAME", $returnedSectionData["SECTION_NAME"]);
    $component->rewriteComponentParams("COUNT", (int)$returnedSectionData["UF_ELEMENTS_NUMBER"]);
    $component->rewriteComponentParams("LIST_VIEW_CONTAINER_ELEMENT_WIDTH_CSS",
        $returnedSectionData["UF_COLUMNS_COUNT"]);
    $component->rewriteComponentParams("LIST_VIEW_TITLE_CSS",
        $returnedSectionData["UF_ELEMENT_TITLE_CSS"]);
    $component->rewriteComponentParams("SHOW_SIDEBAR",
        $returnedSectionData["UF_SIDEBAR_LIST"], true);
    $component->rewriteComponentParams("SIDEBAR_WIDTH", $returnedSectionData["UF_SIDEBAR_WIDTH"]);
    ?>

<?else:?>
    <?$APPLICATION->IncludeComponent("oip:iblock.section.list","",[
        "IBLOCK_ID" => $component->getParam("IBLOCK_ID"),
        "CACHE" => $component->getParam("IS_CACHE")
    ])?>
<?endif?>
