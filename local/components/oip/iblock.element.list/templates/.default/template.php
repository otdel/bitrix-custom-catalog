<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/**
 *
 * <?$APPLICATION->IncludeComponent("oip:iblock.element.list","",[
    "IBLOCK_ID" => 2,
    "SECTION_ID" => 8,
    "SHOW_INACTIVE" => "Y"
    "PROPERTIES" => [9,8,13,14],
    "RESIZE_FILE_PROPS" => [600,600]
    ])?>
 */

/** @var array $arResult */
/** @var \Oip\Custom\Component\Iblock\Element $element */
/** @var \Oip\Custom\Component\Iblock\Element[] $element */
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