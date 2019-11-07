<?php
/** @var $component \COipIblockElementList */
/** @var \Oip\Custom\Component\Iblock\Element $element */
?>

<?$APPLICATION->IncludeComponent("oip:iblock.element.list", "tags-in-list",[
    "IBLOCK_ID" => $element->getProp("TAGS")->getLinkIblockId(),
    "FILTER" => ["ID" => $element->getPropValue("TAGS")],
    "SHOW_ALL" => "Y",
    "IS_CACHE" => $component->getParam("IS_CACHE"),
    "CACHE_TIME" => $component->getParam("CACHE_TIME"),
])?>
