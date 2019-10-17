<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/**
 *
 * <?$APPLICATION->IncludeComponent("oip:iblock.element.one","",[
    "IBLOCK_ID" => 2,
    "ELEMENT_ID" => 4,
    "PROPERTIES" => [9,8,13,14],
    "SHOW_INACTIVE" => "Y"
    "RESIZE_FILE_PROPS" => [600,600]
    ])?>
 */

/** @var array $arResult */
/** @var \Oip\Custom\Component\Iblock\Element $element */
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