<?
use Oip\Custom\Component\Iblock\Section;
/** @var Section $section */?>

<div class="<?=$component->getParam("TITLE_CLASS")?>"><?=$section->getName()?></div>
<p>Картинка раздела: <?=$section->getPictureUrl() ?></p>
<p>Детальная картинка раздела: <?=$section->getDetailPictureUrl() ?></p>
<p>Описание: <?=$section->getDescription() ?></p>

<p>TITLE: <?=$section->getPropValue("UF_TITLE") ?></p>
<p>DESCRIPTION: <?=$section->getPropValue("UF_DESCRIPTION") ?></p>
<p>KEYWORDS: <?=$section->getPropValue("UF_KEYWORDS") ?></p>

<p>Товар или услуга: <?=$section->getPropValue("UF_CATEGORY_TYPE") ?></p>
<p>Иконка категории: <?=$section->getPropValue("UF_CATEGORY_ICON") ?></p>
<p>Видео: <?=$section->getPropValue("UF_VIDEO") ?></p>

<?$APPLICATION->IncludeComponent("oip:relevant.products.likes.category.widget","",[
        "SECTION_ID" => $section->getId()
])?>

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
        "IS_CACHE" => $component->getParam("CACHE"),
        "CACHE_TIME" => $component->getParam("CACHE_TIME"),
        "FILTER" => array("ID" => array_keys($specialOfferElements))
    ]);
endif;?>