<?php
/** @var $component \COipIblockElementList */
/** @var \Oip\Custom\Component\Iblock\Element $element */
?>
<?if($element->getProp("BRANDS")):?>
    <?$APPLICATION->IncludeComponent(
        "oip:iblock.element.list","brands-in-list",
        [
            "IBLOCK_ID" => $element->getProp("BRANDS")->getLinkIblockId(),
            "FILTER" => ["ID" => $element->getPropValue("BRANDS")],
            "SHOW_ALL" => "Y",
            "IS_CACHE" => $component->getParam("IS_CACHE"),
            "CACHE_TIME" => $component->getParam("CACHE_TIME"),
        ]
    )?>
<?endif?>

