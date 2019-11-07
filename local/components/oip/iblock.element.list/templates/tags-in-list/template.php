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
        <div class="uk-panel">
            <span class="uk-margin-small-right" uk-icon="tag"></span>
            <?$key = 0;?>
            <?foreach($elements as $element):?>
                <?if($key):?>,&nbsp;<?endif?><?=$element->getName()?>
                <?$key++;?>
            <?endforeach?>
        </div>
    <?endif?>

<?endif?>



