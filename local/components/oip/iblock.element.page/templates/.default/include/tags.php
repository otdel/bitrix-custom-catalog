<?php
/** @var $component \COipIblockElementList */
/** @var \Oip\Custom\Component\Iblock\Element $element */
/** @var array $arFilterTemplate */
/** @var array $tagsFilter */
?>

<?if($component->getParam("TAGS_IBLOCK_ID") && $tagsFilter && count($tagsFilter) > 1):?>
    <?$APPLICATION->IncludeComponent(
        "oip:iblock.element.list","tags-in-filter",
        [
            "IBLOCK_ID" => $component->getParam("TAGS_IBLOCK_ID"),
            "SHOW_ALL" => "Y",
            "IS_CACHE" => $component->getParam("IS_CACHE"),
            "CACHE_TIME" => $component->getParam("CACHE_TIME"),
            "FILTER_ID" => $filterId,
            "FILTER_PARAMS" => $arFilterTemplate,
            "FILTER" =>  ["ID" => $tagsFilter]
        ]
    )?>
<?endif?>