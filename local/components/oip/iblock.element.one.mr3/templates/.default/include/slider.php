<?php
/** @var $component \COipIblockElementOne */
/** @var \Oip\Custom\Component\Iblock\Element $element */
?>

<div class="uk-panel">

    <div class="uk-position-relative uk-height-1-1" tabindex="-1" uk-slider="center: true">
        <div class="uk-slider-container uk-height-1-1">
            <ul class="uk-slider-items uk-grid uk-height-1-1  uk-grid" uk-lightbox>

                <?if($element->getPropValue("VIDEO")):?>
                    <?$link_convertion = $component->getConvertedVideo($element->getPropValue("VIDEO"))?>
                    <li class="uk-width-3-4 uk-height-1-1">
                        <div class="uk-position-relative uk-width-1-1 uk-height-1-1 uk-overflow-hidden">
                            <iframe width="" height="" src="<?=$link_convertion?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen  uk-video="autoplay: inview"  uk-cover></iframe>
                            <a class="uk-position-cover uk-link-reset" href="<?=$element->getPropValue("VIDEO")?>"></a>
                        </div>
                    </li>
                <?endif?>

                <li class="uk-width-3-4 uk-height-1-1">
                    <div class="uk-position-relative uk-width-1-1 uk-height-1-1 uk-overflow-hidden">
                        <div 
                            class="uk-width-1-1 uk-height-1-1 uk-background-center-center uk-background-norepeat uk-background-cover" 
                            data-src="<?=$element->getDetailPicture()?>" uk-img></div>
                        <a class="uk-position-cover uk-link-reset" href="<?=$element->getDetailPicture()?>"></a>
                    </div>
                </li>

                <?if($element->getPropValue("GALLERY")):?>
                
                    <?foreach($element->getPropValue("GALLERY") as $photo):?>
                        <li class="uk-width-3-4 uk-height-1-1">
                            <div class="uk-position-relative uk-width-1-1 uk-height-1-1 uk-overflow-hidden">
                                <div
                                    class="uk-width-1-1 uk-height-1-1 uk-background-center-center uk-background-norepeat uk-background-cover"
                                    data-src="<?=$photo?>" uk-img></div>
                                <a class="uk-position-cover uk-link-reset" href="<?=$photo?>"></a>
                            </div>
                        </li>
                    <?endforeach?>
                    
                <?endif?>

            </ul>
        </div>

        <div class="uk-hidden@s uk-light">
            <a class="uk-position-center-left uk-position-small" href="#" uk-slidenav-previous uk-slider-item="previous"></a>
            <a class="uk-position-center-right uk-position-small" href="#" uk-slidenav-next uk-slider-item="next"></a>
        </div>

        <div class="uk-visible@s">
            <a class="uk-position-center-left-out" href="#" uk-slidenav-previous uk-slider-item="previous"></a>
            <a class="uk-position-center-right-out" href="#" uk-slidenav-next uk-slider-item="next"></a>
        </div>

        <ul class="uk-slider-nav uk-dotnav uk-flex-center uk-margin"></ul>

    </div>

</div>

