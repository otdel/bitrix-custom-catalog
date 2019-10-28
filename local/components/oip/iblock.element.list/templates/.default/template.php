<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/**
 * это старый пример структуры - массив параметров плоский (кроме properties), вызывать, соединяя детей с родителями через _
 *
 * <?$APPLICATION->IncludeComponent("oip:iblock.element.list","",[
 *  "BASE" => [
        "IBLOCK_ID" => 2,
        "SECTION_ID" => 8,
        "SHOW_INACTIVE" => "Y" - показать и неактивные
        "PROPERTIES" => [
            "PICS_NEWS",
            "TEST_STRING",
            "TEST_FILE",
            "TEST_LIST",
        ],
        "PROPERTIES" => "all" - все свойства
        "RESIZE_FILE_PROPS" => [600,600]
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
 *  ],
 *
 * "LIST_VIEW" => [
        "SHOW_SIDEBAR" => "Y",

        "TITLE" => [
            "TEXT" => "",
            "TAG" => "",
            "CSS" => "",
            "ICON_CSS" => "",
            "ALIGN" => "",
        ],

        "WRAP" => [
            "COLOR" => "",
            "SIZE" => "",
            "ADD_CSS" => "",
        ],
        "CONTAINER" => [
            "WIDTH_CSS" => "",
            "TYPE" => "",
            "MARGIN_CSS" => "",
            "VERTICAL_ALIGN" => ""
        ]
    ],


    "TILE" => [
        "TYPE" => "",
        "PARALLAX" => "",
        "VERTICAL_ALIGN" => "",
        "HORIZONTAL_MARGIN" => "",
        "VERTICAL_MARGIN" => "",
    ],

    "SLIDER" => [
        "SHOW" => "",
        "SHOW_BULLETS" => "",
        "AUTOPLAY" => "",
        "AUTOPLAY_INTERVAL" => "",
        "CENTERED" => "",
        "MOVE_SETS" => "",
        "HIDE_CONTENT" => "",
        "CONTENT_ON_PICTURE" => "",
    ]

])?>

/** @var array $arResult */
/** @var array $arParams */
/** @var $this \CBitrixComponentTemplate */
/** @var $component \COipIblockElementList */
/** @var \Oip\Custom\Component\Iblock\Element $element */
/** @var \Oip\Custom\Component\Iblock\Element[] $elements */
$component = $this->getComponent();
$exception = $arResult["EXCEPTION"];
$errors = $arResult["ERRORS"];
$elements = $arResult["ELEMENTS"];
?>

<?if($exception):?>
    <p><?=$exception?></p>
<?else:?>

    <?if($errors):?>
        <?foreach($errors as $error):?>
            <p><?=$error?></p>
        <?endforeach?>
    <?endif?>

    <?if($elements):?>

        <?foreach($elements as $element):?>
            <h2><?=$element->getName()?></h2>

            <img src="<?=$element->getDetailPicture()?>" width="200" alt="<?=$element->getDetailPictureDescription()?>">

            <div><?=$element->getDetailText()?></div>
            <hr>
        <?endforeach?>

    <?endif?>


<?endif?>