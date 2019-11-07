<?php
/** @var $component \COipIblockElementOne */
/** @var \Oip\Custom\Component\Iblock\Element $element */
?>

<?$APPLICATION->IncludeComponent("oip:iblock.element.list","tags-in-card",[
    "IBLOCK_ID" => $element->getProp("TAGS")->getLinkIblockId(),
    "FILTER" => ["ID" => $element->getPropValue("TAGS")],
    "SHOW_ALL" => "Y",
    "IS_CACHE" => "N"
])?>
