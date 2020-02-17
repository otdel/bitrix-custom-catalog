<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/** @var array $arResult */
/** @var Exception $exception */

$exception = $arResult["EXCEPTION"];
$likes = (is_null($exception)) ? $arResult["LIKES"] : null;
$isLiked = (is_null($exception)) ? $arResult["IS_LIKED"] : null;
$isLikedCss = ($isLiked) ? "uk-button-primary" : "";
$isLikedTooltip = ($isLiked) ? "Убрать из отложенных" : "Отложить";
?>

<?if(!is_null($exception)):?>
    <?$APPLICATION->IncludeComponent("oip:system.exception.viewer","",[
        "EXCEPTION" => $exception
    ])?>
<?else:?>
    <?=$likes?>&nbsp;<button class="uk-icon-button uk-margin-small-right <?=$isLikedCss?>"
                             uk-icon="heart" uk-tooltip="<?=$isLikedTooltip?>"></button>
<?endif?>
