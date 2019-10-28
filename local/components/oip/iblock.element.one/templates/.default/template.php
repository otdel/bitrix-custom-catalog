<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/**
 * это старый пример структуры - массив параметров плоский (кроме properties), вызывать, соединяя детей с родителями через _
 *
 * <?$APPLICATION->IncludeComponent("oip:iblock.element.one","",[
 * "BASE" => [
        "IBLOCK_ID" => 2,
        "ELEMENT_ID" => 4,
        "PROPERTIES" => [
            "PICS_NEWS",
            "TEST_STRING",
            "TEST_FILE",
            "TEST_LIST",
        ],
     *  "PROPERTIES" => "all" - все свойства
        "RESIZE_FILE_PROPS" => [600,600],
 *
 *      "COUNT" => "",
        "SHOW_INACTIVE" => "Y",
        "FILTER" => "",
        "SORT_1" => "BY_1",
        "SORT_2" => "BY_2",
        "SHOW_META" => "",
        "INCLUDE_IBLOCK_CHAIN" => "",
        "SHOW_SORT" => "",
        "SHOW_404" => "",
        "SHOW_PAGER" => "",
        "SHOW_SIDEBAR" => "",
*   ],
 *
 *  "ELEMENT_VIEW" => [
        "PICTURE" => [
            "TYPE" => "",
            "HEIGHT" => "",
            "POSITION" => ""
        ],
        "BLOCK" => [
            "COLOR" => "",
            "SIZE" => "",
        ],
        "TITLE" => [
            "ALIGN" => "",
            "CSS" => "",
        ],

        "SHOW_CATEGORY_NAME" => "",
        "SHOW_TAG_LIST" => "",
        "SHOW_BRAND" => "",
        "SHOW_REVIEWS_NUMBER" => "",

        "READ_MORE_BUTTON" => [
            "SHOW" => "",
            "TEXT" => "",
            "SHOW_HOVER_EFFECT" => "",
        ],
    ],
])?>
*/

/** @var array $arResult */
/** @var array $arParams */
/** @var $this \CBitrixComponentTemplate */
/** @var $component \COipIblockElementOne */
/** @var \Oip\Custom\Component\Iblock\Element $element */
$component = $this->getComponent();
$exception = $arResult["EXCEPTION"];
$errors = $arResult["ERRORS"];
$element = $arResult["ELEMENT"];
?>

<?if($exception):?>
    <p><?=$exception?></p>
<?else:?>

    <?if($errors):?>
        <?foreach($errors as $error):?>
            <p><?=$error?></p>
        <?endforeach?>
    <?endif?>

    <?if($element):?>
        <h1><?=$element->getName()?></h1>

        <img src="<?=$element->getDetailPicture()?>" width="600" alt="<?=$element->getDetailPictureDescription()?>">


        <div>
            <?=$element->getDetailText()?>
        </div>
    <?endif?>

<?endif?>