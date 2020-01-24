<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arResult */
/** @var array $arParams */
/** @var Exception $exception */
/** @var $this CBitrixComponentTemplate */
/** @var $component CSystemExceptionViewer */

$debugMode = ($arParams["DEBUG_MODE"] === "N") ? false : true;
$exception = $arResult["EXCEPTION"];
?>

<?if($debugMode):?>
    <p><?=$exception->getMessage()?></p>
    <p><?=$exception->getFile()?>: <?=$exception->getLine()?></p>
    <pre><?=$exception->getTraceAsString()?></pre>
<?else:?>
    <p><?=$exception->getMessage()?></p>
<?endif?>
