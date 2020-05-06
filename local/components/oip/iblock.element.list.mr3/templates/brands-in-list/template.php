<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/** @var array $arResult */
/** @var \Oip\Custom\Component\Iblock\Element[] $elements */
/** @var \Oip\Custom\Component\Iblock\Element $element */

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
            <?=$element->getName()?>,&nbsp;
        <?endforeach?>
    <?endif?>

<?endif?>


