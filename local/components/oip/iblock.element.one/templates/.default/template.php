<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/** @var array $arResult */
/** @var \Oip\Custom\Component\Iblock\Element $element */

$exception = $arResult["EXCEPTION"];
$errors = $arResult["ERRORS"];
$element = $arResult["ELEMENT"];
?>

<div class="uk-section uk-section-default">

    <div class="uk-container uk-container-large">

        <?if($exception):?>
            <p><?=$exception?></p>
        <?else:?>

            <?if($errors):?>
                <?foreach($errors as $error):?>
                    <p><?=$error?></p>
                <?endforeach?>
            <?endif?>

            <?if($element):?>

                <div class="uk-grid-large uk-grid-match" uk-grid>
                    <div class="uk-width-3-5@m">
                        <?include_once (__DIR__."/include/slider.php")?>
                    </div>

                    <div class="uk-width-2-5@m">
                        <div class="uk-panel">

                            <?if($element->getPropValue("BRANDS")):?>
                                <div class="uk-text-uppercase uk-text-primary uk-text-bold uk-margin-small-bottom">
                                    <?include_once (__DIR__."/include/brands.php")?>
                                </div>
                            <?endif?>

                            <h1 class="uk-h3 uk-margin-remove-bottom">
                                <?=$element->getName()?>
                                <?if($element->getPropValue("BADGE")):?>
                                    <ins class="uk-margin-small-left uk-text-small">
                                        <sup><?=$element->getPropValue("BADGE")?></sup>
                                        </ins>
                                <?endif?>

                            </h1>

                            <?if($element->getSectionName()):?>
                                <p class="uk-margin-small-top uk-margin-medium-bottom  uk-text-small">
                                    <a class="uk-link-reset uk-display-block" href=""><?=$element->getSectionName()?></a>
                                </p>
                            <?endif?>

                            <p class="uk-margin-medium-top uk-text-large uk-text-nowrap price-detail">15 555&nbsp;₽</p>

                            <p>
                                <?=$element->getPreviewText()?>
                            </p>

                            <div class="uk-grid-small uk-child-width-auto uk-flex-middle" uk-grid>
                                <div>
                                    <button class="uk-button uk-button-primary">Добавить в корзину</button>
                                </div>
                                <div>
                                    <button class="uk-icon-button uk-margin-small-right" uk-icon="heart" uk-tooltip="Отложить"></button>
                                </div>
                            </div>

                            <?include_once (__DIR__."/include/bottom.php")?>

                            <?include_once (__DIR__."/include/tags.php")?>

                        </div>
                    </div>

                </div>

                <div class="uk-margin-large-top">
                    <?=$element->getDetailText()?>
                </div>
            <?endif?>

        <?endif?>

    </div>
</div>
