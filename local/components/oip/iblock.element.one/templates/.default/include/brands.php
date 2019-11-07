<?php
/** @var \Oip\Custom\Component\Iblock\Element $element */
?>
<?$APPLICATION->IncludeComponent(
    "oip:iblock.element.list","brands-in-card",
    [
        "IBLOCK_ID" => $element->getProp("BRANDS")->getLinkIblockId(),
        "FILTER" => ["ID" => $element->getPropValue("BRANDS")],
        "IS_CACHE" => "N",
        "SHOW_ALL" => "Y",
    ]
)?>