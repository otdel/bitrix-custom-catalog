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

        <div class="uk-panel">
            <ul class="uk-list uk-list-divider uk-text-small">

        <?foreach($elements as $element):?>
            <li>
                <div class="uk-panel">

                    <div class="uk-h6 uk-margin-remove"><b><?=$element->getName()?></b></div>

                    <p class="uk-margin-remove">
                        <?=$element->getPreviewText()?>
                    </p>

                </div>
            </li>
        <?endforeach?>

            </ul>
        </div>

    <?endif?>

<?endif?>
