<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/** @var array $arResult */
/** @var Exception $exception */

$exception = $arResult["EXCEPTION"];
?>

<?if(!is_null($exception)):?>
    <?$APPLICATION->IncludeComponent("oip:system.exception.viewer","",[
            "EXCEPTION" => $exception
    ])?>
<?else:?>
    <div class="uk-padding"><?=$arResult["LIKES"]?></div>
<?endif?>

