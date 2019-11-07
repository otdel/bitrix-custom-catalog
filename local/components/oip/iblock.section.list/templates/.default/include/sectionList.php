<?php
use Oip\Custom\Component\Iblock\Section;
/**
 * Рекурсивная функция вывода раздела и его подразделов
 *
 * @param $component \COipIblockSectionList Компонент (чтобы читать параметры)
 * @param Section[] $sections Массив разделов, который выводим
 * @param boolean $isSubSection Является ли выводимый раздел дочерним (По факту - рекурсивный ли это вызов функции или самый первый)
 */
function printSection($component, $sections, $isSubSection)
{ ?>

    <?
    // Для каждого раздела выводим определенные поля, а также подразделы (рекурсивно)
    /** @var Section $section */
    foreach ($sections as $section) {

        $subSections = $section->getSubSections(); ?>

        <!-- Обычные ссылки -->
        <li<?if(isset($subSections) && !$isSubSection):?> class="uk-parent"<?endif;?>>
            <a href="<?= $section->getSectionPageUrl()?>">

                <?if($component->getParam("SHOW_PREVIEW") && ($pictureUrl = $section->getPictureUrl())):?>

                    <div class="uk-grid-small uk-flex-middle" uk-grid>
                        <div class="uk-width-auto">
                            <div class="preview preview-small
                                <?=$component->getParam("PREVIEW_PICTURE_HEIGHT_CLASS")?>
                                <?=$component->getParam("PREVIEW_PICTURE_WIDTH_CLASS")?>">
                                <div class="preview-img" data-src="<?=$pictureUrl?>" uk-img>
                                </div>
                            </div>
                        </div>
                        <div class="uk-width-expand">
                            <div class="uk-h6 uk-margin-remove"><?= $section->getName() ?> (ID: <?=$section->getId()?>)</div>
                        </div>
                    </div>

                <?else:?>

                    <?= $section->getName() ?>

                <?endif;?>
            </a>

            <!-- Подразделы -->
            <?if (isset($subSections)):?>

                <ul class="uk-nav-sub">
                    <?printSection($component, $subSections, true);?>
                </ul>

            <?endif;?>

        </li>

    <? } ?>

<?}?>

<?/** @var $component \COipIblockSectionList */?>
<div class="<?=$component->getParam("TITLE_CLASS")?>"><?=$component->getParam("TITLE_TEXT")?></div>

<?if($component->getParam("VIEW_TYPE") == "SLIDER"):?>
<div uk-slider>
    <div class="uk-slider-container">
        <?endif;?>

        <ul class="<?= $component->getParam("LIST_TYPE") ?>
           <?= $component->getParam("LIST_CLASS") ?>
           <?= $component->getParam("LIST_ADDITIONAL_CLASS") ?>
           <? if ($component->getParam("VIEW_TYPE") == "SLIDER"): ?> uk-slider-items uk-flex uk-flex-nowrap preview-nav <? endif; ?>"
            <?= $component->getParam("LIST_ATTRIBUTE") ?>>

            <?printSection($component, $arResult["SECTIONS"], false);?>

        </ul>

        <?if($component->getParam("VIEW_TYPE") == "SLIDER"):?>
        <div class="uk-visible@s">
            <a class="uk-position-center-left-out" href="#" uk-slidenav-previous uk-slider-item="previous"></a>
            <a class="uk-position-center-right-out" href="#" uk-slidenav-next uk-slider-item="next"></a>
        </div>
    </div>
</div>
<?endif;?>