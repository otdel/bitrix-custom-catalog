<?php
/** @var $component \COipIblockElementList */
/** @var \Oip\Custom\Component\Iblock\Element $element */
?>

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
                <?include(__DIR__."/section.php")?>
            <?endif?>

            <?if($component->isParam("ELEMENT_VIEW_SHOW_BRAND")):?>
                <li>
                    <?include(__DIR__."/brands.php")?>
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

    <?if($component->isParam("ELEMENT_VIEW_SHOW_TAG_LIST")
        && $element->getPropValueCount("TAGS")
        || $component->isParam("ELEMENT_VIEW_SHOW_REVIEWS_NUMBER")
        &&  $element->getPropValueCount("REVIEWS")
    ):?>
        <div class="uk-card uk-card-footer uk-text-meta">
            <div class="uk-grid-small uk-flex-middle" uk-grid>

                <?if($component->isParam("ELEMENT_VIEW_SHOW_TAG_LIST")
                    &&  $element->getPropValueCount("TAGS")
                ):?>
                    <div class="uk-width-expand">
                        <?include(__DIR__."/tags.php")?>
                    </div>
                <?endif?>

                <?if($component->isParam("ELEMENT_VIEW_SHOW_REVIEWS_NUMBER")
                    && $element->getPropValueCount("REVIEWS")
                ):?>
                    <div class="uk-width-auto">
                        <span class="uk-margin-small-right" uk-icon="comment"></span><?=$element->getPropValueCount("REVIEWS")?>
                    </div>
                <?endif?>

            </div>
        </div>
    <?endif?>


</div>
