<?php
/** @var \Oip\Custom\Component\Iblock\Element $element */
/** @var $component \COipIblockElementOne */
?>

<?if($element->getProp("BRANDS")):?>
    <?$APPLICATION->IncludeComponent(
        "oip:iblock.element.list","brands-in-card",
        [
            "IBLOCK_ID" => $element->getProp("BRANDS")->getLinkIblockId(),
            "FILTER" => ["ID" => $element->getPropValue("BRANDS")],
            "IS_CACHE" => $component->getParam("IS_CACHE"),
            "CACHE_TIME" => $component->getParam("CACHE_TIME"),
            "SHOW_ALL" => "Y",
        ]
    )?>
<?endif?>
