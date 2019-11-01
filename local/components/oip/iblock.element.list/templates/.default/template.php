<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/** @var array $arResult */
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

        <?if($component->isContainerSlider()):?>

            <div uk-slider="
                autoplay: <?=$component->getParam("SLIDER_AUTOPLAY")?>;
                autoplay-interval: <?=$component->getParam("SLIDER_AUTOPLAY_INTERVAL")?>;
                center: <?=$component->getParam("SLIDER_CENTERED")?>;
                sets: <?=$component->getParam("SLIDER_MOVE_SETS")?>;
            ">

                <div class="uk-position-relative">

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

                             <?if(!$element->getPropValue("VIDEO")):?>
                                 uk-slider="autoplay:true; autoplay-interval:3500"
                             <?endif?>
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

                            <!-- card-body start-->
                            <div class="uk-card-body">

                                <div class="
                                    uk-margin-remove
                                    uk-<?=$component->getParam("ELEMENT_VIEW_TITLE_CSS")?>
                                    uk-text-<?=$component->getParam("ELEMENT_VIEW_TITLE_ALIGN")?>
                                ">
                                    <?=$element->getName()?>
                                </div>

                                <?if($component->isParam("ELEMENT_VIEW_SHOW_CATEGORY_NAME")
                                    || $component->isParam("ELEMENT_VIEW_SHOW_BRAND")):?>

                                    <ul class="uk-subnav uk-subnav-divider uk-margin-small-top uk-text-small uk-flex-center">
                                        <?if($component->isParam("ELEMENT_VIEW_SHOW_CATEGORY_NAME")):?>
                                            <li>
                                                холодильники
                                            </li>
                                        <?endif?>

                                        <?if($component->isParam("ELEMENT_VIEW_SHOW_BRAND")):?>
                                            <li>
                                                Саратов
                                            </li>
                                        <?endif?>
                                    </ul>
                                <?endif?>

                                <?if($component->isParam("ELEMENT_VIEW_SHOW_READ_MORE_BUTTON")):?>
                                    <p class="uk-margin-medium-top uk-text-center">
                                        <button class="uk-button uk-button-default">
                                            <?=$component->getParam("ELEMENT_VIEW_READ_MORE_BUTTON_TEXT")?>
                                        </button>
                                    </p>
                                <?endif?>

                                <div class="uk-card uk-card-footer uk-text-meta">
                                    <div class="uk-grid-small uk-flex-middle" uk-grid>

                                        <!-- Только если показывать теги-->
                                        <div class="uk-width-expand">
                                            <div class="uk-panel">
                                                <span class="uk-margin-small-right" uk-icon="tag"></span>Розовый холодильник, красивый холодильник
                                            </div>
                                        </div>

                                        <!-- Только если показывать кол-во отзывов-->
                                        <div class="uk-width-auto">
                                            <span class="uk-margin-small-right" uk-icon="comment"></span>5
                                        </div>

                                    </div>
                                </div>

                            </div>
                            <!-- Card-body end -->

                        <?endif?>

                    </div>

                </a>
            </li>
        
        <?endforeach?>

        </ul>

            <?if($component->isContainerSlider()):?>

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
                </div>
            </div>


        <?endif?>

    <?endif?>

<?endif?>