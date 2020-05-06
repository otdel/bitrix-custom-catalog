<?php
/** @var $component \COipIblockElementList */
/** @var \Oip\Custom\Component\Iblock\Element $element */
?>


<?if($element->getPreviewPicture()):?>


    <?if($element->getPropValue("BADGE")):?>
        <div class="uk-position-top-right">

                            <span class="uk-label uk-position-small">
                                <?=$element->getPropValue("BADGE")?>
                            </span>

        </div>
    <?endif?>


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
                    <?if($component->isParam("ELEMENT_VIEW_SHOW_READ_MORE_BUTTON")):?>
                        <p class="uk-margin-medium-top uk-text-center">
                            <button class="uk-button uk-button-secondary">
                                <?=$component->getParam("ELEMENT_VIEW_READ_MORE_BUTTON_TEXT")?>
                            </button>
                        </p>
                    <?endif?>
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

<?endif?>

