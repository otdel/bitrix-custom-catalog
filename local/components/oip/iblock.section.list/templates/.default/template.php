<?php

use Oip\Custom\Component\Iblock\Section;

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

/** @var $this \CBitrixComponentTemplate */
/** @var $component \COipIblockSectionList */
$component = $this->getComponent();

/** @var array $arResult */
/** @var Section[] $sections */
$sections = $arResult["SECTIONS"];
?>

<?php
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


<? if ($component->isSingleSection()):
    $section = $arResult["SECTIONS"][0];
    /** @var Section $section */ ?>

    <div class="<?=$component->getParam("TITLE_CLASS")?>"><?=$section->getName()?></div>
    <p>Картинка раздела: <?=$section->getPictureUrl() ?></p>
    <p>Детальная картинка раздела: <?=$section->getDetailPictureUrl() ?></p>
    <p>Описание: <?=$section->getDescription() ?></p>

    <p>TITLE: <?=$section->getPropValue("UF_BROWSER_TITLE") ?></p>
    <p>DESCRIPTION: <?=$section->getPropValue("UF_META_DESCRIPTION") ?></p>
    <p>KEYWORDS: <?=$section->getPropValue("UF_KEYWORDS") ?></p>

    <p>Товар или услуга: <?=$section->getPropValue("UF_TEST_LIST") ?></p>
    <p>Иконка категории: <?=$section->getPropValue("UF_CATEGORY_ICON") ?></p>
    <p>Видео: <?=$section->getPropValue("UF_VIDEO") ?></p>

    <?if ($files = $section->getPropValue("UF_DOCUMENTS")):
        foreach ($files as $file):?>
            <p>Документ: <?=$file ?></p>
        <?endforeach;
    endif?>


    <?if ($files = $section->getPropValue("UF_GALLERY")):
        foreach ($files as $file):?>
            <p>Файл галереи: <?=$file ?></p>
        <?endforeach;
    endif?>

    <?if ($specialOfferElements = $section->getPropValue("UF_TEST_ELEMENTS")):?>
        <p>Спецпредложения:</p>
    <?$APPLICATION->IncludeComponent("oip:iblock.element.list","",[
        "IBLOCK_ID" => 1,
        "COUNT" => 10,
        "SHOW_ALL" => "Y",
        "IS_CACHE" => "N",
        "CACHE_TIME" => 60,
        "FILTER" => array("ID" => array_keys($specialOfferElements))
    ]);
    endif;?>

<? else: ?>

    <div class="<?=$component->getParam("TITLE_CLASS")?>"><?=$component->getParam("TITLE_TEXT")?></div>

    <?if($component->getParam("VIEW_TYPE") == "SLIDER"):?>

        <div uk-slider>
        <div class="uk-slider-container">

    <?endif;?>

    <!-- Общее для слайдеров и списков. В данном примере аккордеон -->
    <ul class="<?= $component->getParam("LIST_TYPE") ?>
           <?= $component->getParam("LIST_CLASS") ?>
           <?= $component->getParam("LIST_ADDITIONAL_CLASS") ?>
           <? if ($component->getParam("VIEW_TYPE") == "SLIDER"): ?> uk-slider-items uk-flex uk-flex-nowrap preview-nav <? endif; ?>"
        <?= $component->getParam("LIST_ATTRIBUTE") ?>
    >

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


<? endif; ?>


