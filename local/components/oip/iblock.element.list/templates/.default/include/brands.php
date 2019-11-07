<?php
/** @var \Oip\Custom\Component\Iblock\Element $element */
?>
<?$APPLICATION->IncludeComponent(
    "oip:iblock.element.list","brands-in-list",
    [
        "IBLOCK_ID" => $element->getProp("BRANDS")->getLinkIblockId(),
        "FILTER" => ["ID" => $element->getPropValue("BRANDS")],
        "SHOW_ALL" => "Y",
        "IS_CACHE" => "N",
    ]
)?>
