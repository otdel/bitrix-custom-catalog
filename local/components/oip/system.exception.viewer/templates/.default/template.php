<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arResult */
/** @var array $arParams */
/** @var Exception $exception */
/** @var $this CBitrixComponentTemplate */
/** @var $component CSystemExceptionViewer */

$debugMode = ($arParams["DEBUG_MODE"] === "N") ? false : true;
$exception = $arResult["EXCEPTION"];
$customMessage = $arParams["CUSTOM_MESSAGE"];
?>

<?if($customMessage):?>
<div class="uk-alert-danger" uk-alert>
    <p><?=htmlspecialcharsback($customMessage)?></p>
</div>
<?else:?>
    <div class="uk-alert-danger" uk-alert>
        <p><?=$exception->getMessage()?></p>
    </div>
    <?if($debugMode):?>
        <div uk-alert>
                <pre><?=$exception->getTraceAsString()?></pre>
        </div>
    <?endif?>
<?endif?>
