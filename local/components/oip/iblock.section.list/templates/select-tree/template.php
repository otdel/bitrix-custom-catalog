<?php
use Oip\Custom\Component\Iblock\Section;

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

/** @var array $arResult */
/** @var Section[] $sections */
/** @var $this \CBitrixComponentTemplate */
/** @var $component \COipIblockSectionList */
$sections = $arResult["SECTIONS"];
$filterId = $component->getParam("FILTER_ID");
$arFilterParams = $component->getParam("FILTER_PARAMS");

$isSectionSelected =  array_key_exists("f".$filterId."_fSECTION-ID", $arFilterParams);
?>

<?if($sections):?>
    <select class="uk-select" name="f<?=$filterId?>_fSECTION-ID" id="oip-section-filter" data-filter-id="<?=$filterId?>">

        <option value="">Любая категория</option>

        <?foreach($sections as $section):?>

            <?
            $isActive = (
                $isSectionSelected
                && $section->getId() == reset($arFilterParams["f".$filterId."_fSECTION-ID"])
            ) ? true : false;
            ?>

            <option class="select-header" value="<?=$section->getId()?>" <?if($isActive):?>selected<?endif?>>
                <?=$section->getName()?>
            </option>

            <?foreach($section->getSubSections() as $subSection):?>
                <?
                $isActive = (
                    $isSectionSelected
                    && $subSection->getId() ==  reset($arFilterParams["f".$filterId."_fSECTION-ID"])
                ) ? true : false;
                ?>
                <option value="<?=$subSection->getId()?>" <?if($isActive):?>selected<?endif?>><?=$subSection->getName()?></option>
            <?endforeach?>

        <?endforeach?>
    </select>
<?endif?>

