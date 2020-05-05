<?php
/** @var array $arResult */
/** @var $this \CBitrixComponentTemplate */
/** @var $component \COipIblockElementList */
/** @var \Oip\Custom\Component\Iblock\Element $element */
/** @var string $sectionName */
?>

<?if($component->isParam("ELEMENT_VIEW_SHOW_CATEGORY_NAME")):?>
    <?if($component->getParam("SECTION_NAME")):?>
        <li><?=$component->getParam("SECTION_NAME")?></li>
    <?elseif($component->getParam("SECTION_ID") && $sectionName):?>
        <li><?=$sectionName?></li>
    <?else:?>
        <li><?=$element->getSectionName()?></li>
    <?endif?>
<?endif?>
