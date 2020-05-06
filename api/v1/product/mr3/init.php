<?php

use Bitrix\Main\Loader;
use Oip\ProductFeature\DataWrapper;
use Oip\ProductFeature\Repository\DBRepository;

$iblockId = (int)$_REQUEST["iblockId"];
$productId = (int)$_REQUEST["productId"];
$wareId = (int)$_REQUEST["wareId"];

if(!$iblockId) {
    throw new InvalidArgumentException("Invalid iblockId. Check if you pass it.");

}if(!$productId) {
    throw new InvalidArgumentException("Invalid productId. Check if you pass it.");

}
if(!$wareId) {
    throw new InvalidArgumentException("Invalid wareId. Check if you pass it.");

}

global $DB;

$rep = new DBRepository($DB);
$dw = new DataWrapper($rep);
Loader::includeModule("iblock");
