<?php
/** @var $component \COipIblockElementOne */
/** @var \Oip\Custom\Component\Iblock\Element $element */
?>

<?$APPLICATION->IncludeComponent("oip:iblock.element.list","reviews-in-card",[
    "IBLOCK_ID" => $element->getProp("REVIEWS")->getLinkIblockId(),
    "FILTER" => ["ID" => $element->getPropValue("REVIEWS")],
    "SORT_1" => "active_from",
    "BY_1" => "DESC",
    "SHOW_ALL" => "Y",
    "IS_CACHE" => $component->getParam("IS_CACHE"),
    "CACHE_TIME" => $component->getParam("CACHE_TIME")
])?>
