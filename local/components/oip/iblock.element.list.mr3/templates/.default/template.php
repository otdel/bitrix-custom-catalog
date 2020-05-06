<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/** @var array $arResult */
/** @var $this \CBitrixComponentTemplate */
/** @var $component \COipIblockElementList */
/** @var \Oip\Custom\Component\Iblock\Element $element */
/** @var \Oip\Custom\Component\Iblock\Element[] $elements */
/** @var string $sectionName */

$component = $this->getComponent();
$exception = $arResult["EXCEPTION"];
$errors = $arResult["ERRORS"];
$elements = $arResult["ELEMENTS"];
$sectionName = $arResult["SECTION_NAME"];
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

        <?if($component->isContainerSlider()):?>

            <?if($component->getParam("SLIDER_AUTOPLAY") || $component->getParam("SLIDER_CENTERED") || $component->getParam("SLIDER_MOVE_SETS")):?>

                <div class="uk-position-relative"  tabindex="-1" uk-slider="

                    <?if($component->getParam("SLIDER_AUTOPLAY")):?>
                        autoplay: <?=$component->getParam("SLIDER_AUTOPLAY")?>;
                        autoplay-interval: <?=$component->getParam("SLIDER_AUTOPLAY_INTERVAL")?>;
                    <?endif?>

                    <?if($component->getParam("SLIDER_CENTERED")):?>
                        center: <?=$component->getParam("SLIDER_CENTERED")?>;
                    <?endif?>

                    <?if($component->getParam("SLIDER_MOVE_SETS")):?>
                        sets: <?=$component->getParam("SLIDER_MOVE_SETS")?>;
                    <?endif?>
                ">
            <?else:?>
                <div class="uk-position-relative" tabindex="-1" uk-slider>
            <?endif?>
            <div class="uk-slider-container shadow-fix">

        <?endif?>


        <ul
                class="
           <?=$component->getParam("LIST_VIEW_CONTAINER_ELEMENT_WIDTH_CSS")?>

        uk-grid-<?=$component->getParam("LIST_VIEW_CONTAINER_MARGIN_CSS")?>
        <?if($component->isParam("LIST_VIEW_CONTAINER_VERTICAL_ALIGN")):?> uk-grid-match <?endif?>

        <?if(!$component->isContainerSlider()):?>
            uk-grid-column-<?=$component->getParam("TILE_VERTICAL_MARGIN")?>
            uk-grid-row-<?=$component->getParam("TILE_HORIZONTAL_MARGIN")?>
            uk-flex-<?=$component->getParam("TILE_VERTICAL_ALIGN")?>
        <?else:?>
            uk-slider-items
        <?endif?>

        "
            <?if(!$component->isContainerSlider()):?>
                uk-grid="
                masonry: <?=$component->getParam("TILE_DYNAMIC")?>;
                parallax: <?=$component->getParam("TILE_PARALLAX")?>"
            <?endif?>
        >

            <?foreach($elements as $element):?>

                <li>
                    <a class="uk-link-reset uk-display-block uk-position-relative" href="<?=$element->getDetailUrl()?>">

                        <div class="
                    uk-height-1-1
                    uk-card
                    uk-card-<?=$component->getParam("ELEMENT_VIEW_BLOCK_SIZE")?>
                    uk-card-<?=$component->getParam("ELEMENT_VIEW_BLOCK_COLOR")?>
                    uk-flex
                    <?=$component->getCardPositionCss()?>
            ">


                            <div class="
                            uk-position-relative uk-overflow-hidden uk-animation-toggle uk-visible-toggle
                            uk-card-media-<?=$component->getParam("ELEMENT_VIEW_PICTURE_POSITION")?>
                            uk-height-<?=$component->getParam("ELEMENT_VIEW_PICTURE_HEIGHT")?>
                                "
                            >

                                <?if($element->getPropValue("VIDEO")):?>
                                    <?$link_convertion = $component->getConvertedVideo($element->getPropValue("VIDEO"))?>
                                    <iframe width="" height="" src="<?=$link_convertion?>" frameborder="0"
                                            allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                                            allowfullscreen  uk-video="autoplay: inview"  uk-cover></iframe>
                                <?else:?>

                                    <?include(__DIR__."/include/gallery.php")?>

                                <?endif?>

                            </div>

                            <?if($component->isContainerSlider() && !$component->isParam("SLIDER_CONTENT_ON_PICTURE")
                                || !$component->isContainerSlider()):?>

                                <?include(__DIR__."/include/body.php")?>

                            <?$APPLICATION->IncludeComponent("oip:social.store.cart.button","",[
                                    "PRODUCT_ID" => $element->getId(),
                                    "BUTTON_TEXT_ADD" => "В корзину",
                                    "BUTTON_TEXT_REMOVE" => "В корзине",
                                    "BUTTON_ICON_ADD" => "cart",
                                    "BUTTON_ICON_REMOVE" => "close"
                            ])?>

                            <?endif?>

                        </div>

                    </a>
                </li>

            <?endforeach?>

        </ul>

        <?if($component->isContainerSlider()):?>
            </div>

            <?if($component->isParam("SLIDER_SHOW_ARROWS")):?>

                <div class="uk-hidden@s uk-light">
                    <a class="uk-position-center-left uk-position-small" href="#" uk-slidenav-previous uk-slider-item="previous"></a>
                    <a class="uk-position-center-right uk-position-small" href="#" uk-slidenav-next uk-slider-item="next"></a>
                </div>

                <div class="uk-visible@s">
                    <a class="uk-position-center-left-out" href="#" uk-slidenav-previous uk-slider-item="previous"></a>
                    <a class="uk-position-center-right-out" href="#" uk-slidenav-next uk-slider-item="next"></a>
                </div>

            <?endif?>

            <?if($component->isParam("SLIDER_SHOW_BULLETS")):?>
                <ul class="uk-slider-nav uk-dotnav uk-flex-center uk-margin"></ul>
            <?endif?>
            </div>
        <?endif?>

    <?endif?>

<?endif?>