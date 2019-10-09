<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;

try {
    Loader::registerAutoLoadClasses(null, [
        "Oip\\Iblock\\ElementPropertyTable" => "/local/modules/oip.iblock/lib/ElementPropertyTable.php"
    ]);
}
catch(LoaderException $e) {
    var_dump($e->getMessage());
}