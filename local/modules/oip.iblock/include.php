<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;

try {
    Loader::registerAutoLoadClasses(null, [
        "Bitrix\\Iblock\\ElementTable" => "/bitrix/modules/iblock/lib/element.php",
        "Bitrix\\Iblock\\PropertyTable" => "/bitrix/modules/iblock/lib/property.php",

        "Oip\\Iblock\\Element\\ElementTable" => "/local/modules/oip.iblock/lib/Element/ElementTable.php",
        "Oip\\Iblock\\Element\\Element" => "/local/modules/oip.iblock/lib/Element/Element.php",
        "Oip\\Iblock\\Element\\ElementCollection" => "/local/modules/oip.iblock/lib/Element/ElementCollection.php",

        "Oip\\Iblock\\ElementProperty\\ElementPropertyTable" => "/local/modules/oip.iblock/lib/ElementProperty/ElementPropertyTable.php",
        "Oip\\Iblock\\ElementProperty\\ElementProperty" => "/local/modules/oip.iblock/lib/ElementProperty/ElementProperty.php",
        "Oip\\Iblock\\ElementProperty\\ElementPropertyCollection" => "/local/modules/oip.iblock/lib/ElementProperty/ElementPropertyCollection.php",

        "Oip\\Iblock\\Property\\PropertyTable" => "/local/modules/oip.iblock/lib/Property/PropertyTable.php",
        "Oip\\Iblock\\Property\\Property" => "/local/modules/oip.iblock/lib/Property/Property.php",
        "Oip\\Iblock\\Property\\PropertyCollection" => "/local/modules/oip.iblock/lib/Property/PropertyCollection.php",
    ]);
}
catch(LoaderException $e) {
    var_dump($e->getMessage());
}