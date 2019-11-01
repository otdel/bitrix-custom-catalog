<?php
/** @var $component \COipIblockElementList */
/** @var \Oip\Custom\Component\Iblock\Element $element */
?>

<ul class="uk-slider-items uk-child-width-1-1">
    <?if($element->getPreviewPicture()):?>
        <li>
            <div
                    class="
                uk-width-1-1 uk-background-center-center uk-background-norepeat
                uk-height-<?=$component->getParam("ELEMENT_VIEW_PICTURE_HEIGHT")?>
                uk-background-<?=$component->getParam("ELEMENT_VIEW_PICTURE_TYPE")?>
            "
                    data-src="<?=$element->getPreviewPicture()?>" uk-img
            >

                <?if($component->isParam("SLIDER_CONTENT_ON_PICTURE")):?>
                    <div class="uk-position-bottom">
                        <div class="uk-overlay uk-overlay-default">
                            <div class="
                            uk-<?=$component->getParam("ELEMENT_VIEW_TITLE_CSS")?>
                            uk-text-<?=$component->getParam("ELEMENT_VIEW_TITLE_ALIGN")?>
                            "><?=$element->getName()?></div>
                        </div>
                    </div>
                <?endif?>

                <?if($component->isParam("ELEMENT_VIEW_SHOW_HOVER_EFFECT")):?>
                    <div class="
                    uk-position-cover uk-overlay-primary uk-hidden-hover
                    uk-animation-<?=$component->getParam("ELEMENT_VIEW_HOVER_EFFECT_CSS")?>
                ">
                        <div class="uk-position-center">
                            <span uk-overlay-icon></span>
                        </div>
                    </div>
                <?endif?>

            </div>
        </li>
    <?endif?>

    <?if($element->getPropValue("GALLERY")):?>

        <?for($i = 0; $i < count($element->getPropValue("GALLERY")); $i++) {
            $photoSrc = $element->getPropValueFromMultiple("GALLERY", $i);
            $photoDescription = $element->getPropValueDescriptionFromMultiple("GALLERY", $i);
            ?>

            <li>
                <div
                        class="
                uk-width-1-1 uk-background-center-center uk-background-norepeat
                uk-height-<?=$component->getParam("ELEMENT_VIEW_PICTURE_HEIGHT")?>
                uk-background-<?=$component->getParam("ELEMENT_VIEW_PICTURE_TYPE")?>
            "
                        data-src="<?=$photoSrc?>" uk-img
                >

                    <?if($component->isParam("SLIDER_CONTENT_ON_PICTURE")):?>
                        <div class="uk-position-bottom">
                            <div class="uk-overlay uk-overlay-default">
                                <div class="
                            uk-<?=$component->getParam("ELEMENT_VIEW_TITLE_CSS")?>
                            uk-text-<?=$component->getParam("ELEMENT_VIEW_TITLE_ALIGN")?>
                            "><?=$photoDescription?></div>
                            </div>
                        </div>
                    <?endif?>

                    <?if($component->isParam("ELEMENT_VIEW_SHOW_HOVER_EFFECT")):?>
                        <div class="
                    uk-position-cover uk-overlay-primary uk-hidden-hover
                    uk-animation-<?=$component->getParam("ELEMENT_VIEW_HOVER_EFFECT_CSS")?>
                ">
                            <div class="uk-position-center">
                                <span uk-overlay-icon></span>
                            </div>
                        </div>
                    <?endif?>

                </div>
            </li>

        <?}?>

    <?endif?>
</ul>

<div class="uk-light">
    <button class="uk-position-center-left uk-position-small" uk-slidenav-previous uk-slider-item="previous"></button>
    <button class="uk-position-center-right uk-position-small" uk-slidenav-next uk-slider-item="next"></button>
</div>
