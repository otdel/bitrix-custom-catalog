<?php
/** @var $component \COipIblockElementOneMR3 */

use Oip\Custom\Component\Iblock\Element;
use Oip\ProductFeature\SectionFeatureOption;

/** @var SectionFeatureOption[] $sectionFeatureOptions */
$sectionFeatureOptions = $arResult["SECTION_FEATURE_OPTIONS"];

/** @var Element $element */
$element = $arResult["ELEMENT"];
?>

<h4>Цвет</h4>
<select name="color">
    <option><?=$element->getProp("COLOR")->getValue()?></option>
    <option>Цвет - 1</option>
    <option>Цвет - 2</option>
    <option>Цвет - 3</option>
</select>

<? foreach ($sectionFeatureOptions as $sectionFeatureOption): ?>
    <h4><?=$sectionFeatureOption->getFeatureName()?></h4>
    <select name="<?=$sectionFeatureOption->getFeatureCode()?>">
        <option><?=$sectionFeatureOption->getFeatureCode()?> - 1</option>
        <option><?=$sectionFeatureOption->getFeatureCode()?> - 2</option>
        <option><?=$sectionFeatureOption->getFeatureCode()?> - 3</option>
    </select>
<? endforeach; ?>

<h4>Гарантия</h4>
<?=$element->getProp("GUARANTEE")->getValue()?>

<h4>Код товара в MR3</h4>
<?=$element->getProp("WARE_ID")->getValue()?>

<h4>Артикул</h4>
<?=$element->getProp("ARTICLE")->getValue()?>

