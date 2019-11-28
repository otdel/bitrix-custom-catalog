<?php
/** @var $component \COipIblockElementList */
/** @var \Oip\Custom\Component\Iblock\Element $element */
/** @var int $filterId */
/** @var array $arFilterTemplate */
?>

<?if($component->getParam("BRANDS_IBLOCK_ID")):?>
    <?$APPLICATION->IncludeComponent(
        "oip:iblock.element.list","brands-in-filter",
        [
            "IBLOCK_ID" => $component->getParam("BRANDS_IBLOCK_ID"),
            "SHOW_ALL" => "Y",
            "IS_CACHE" => $component->getParam("IS_CACHE"),
            "CACHE_TIME" => $component->getParam("CACHE_TIME"),
            "FILTER_ID" => $filterId,
            "FILTER_PARAMS" => $arFilterTemplate
        ]
    )?>
<?endif?>

