<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/** @var array $arResult */
/** @var $this \CBitrixComponentTemplate */
/** @var $component \COipIblockElementList */
/** @var \Oip\Custom\Component\Iblock\Element $element */
/** @var \Oip\Custom\Component\Iblock\Element[] $elements */

$component = $this->getComponent();
$exception = $arResult["EXCEPTION"];
$errors = $arResult["ERRORS"];
$elements = $arResult["ELEMENTS"];
$filterId = $component->getParam("FILTER_ID");
$arFilterParams = $component->getParam("FILTER_PARAMS");
?>

<?if($exception):?>
    <p><?=$exception?></p>
<?else:?>

    <?if($errors):?>
        <?foreach($errors as $error):?>
            <p><?=$error?></p>
        <?endforeach?>
    <?endif?>

    <?$arFilterParams = $APPLICATION->IncludeComponent("oip:filter.processor","",[
        "FILTER_ID" => $filterId,
        "MODE" => "TEMPLATE"
    ])?>

    <input type="hidden" name="data-filter-id" id="data-filter-id" value="<?=$filterId?>">

    <?if($elements):?>

        <div class="uk-panel">
            <ul class="uk-subnav uk-subnav-divider">

                <?foreach($elements as $element):?>

                    <?
                    $isActive = (
                        array_key_exists("f".$filterId."_pTAGS",$arFilterParams)
                        && in_array($element->getId(),$arFilterParams["f".$filterId."_pTAGS"])
                    ) ? true : false;
                    ?>

                    <li <?if($isActive):?>class="uk-active"<?endif?>>
                        <a href="javascript:void(0);"
                           class="uk-text-lowercase oip-filter-tag-item"
                           data-filter-id="<?=$filterId?>"
                           data-tag-id="<?=$element->getId()?>"
                        ><?=$element->getName()?></a>

                        <?if($isActive):?>
                            <i class="uk-icon-button uk-margin-small-left oip-filter-tag-item-reset" uk-icon="close"
                               data-filter-id="<?=$filterId?>" data-tag-id="<?=$element->getId()?>"></i>
                        <?endif?>
                    </li>
                <?endforeach?>

            </ul>
        </div>

    <?endif?>

<?endif?>