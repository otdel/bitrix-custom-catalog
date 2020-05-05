<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

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
        <ul class="uk-margin-medium-top uk-subnav uk-subnav-divider" uk-margin>
            <?foreach($elements as $element):?>
                <li>
                    <a href="<?=$element->getDetailUrl()?>"><?=$element->getName()?></a>
                </li>
            <?endforeach?>
        </ul>
    <?endif?>
<?endif?>
