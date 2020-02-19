<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Oip\RelevantProducts\DataWrapper;

/** @var array $arResult */
/** @var Exception $exception */
/** @var CBitrixComponentTemplate $this  */
/** @var CRelevantProductsLikesCategoryWidget $component  */

$component = $this->getComponent();
$exception = $arResult["EXCEPTION"];
$likes = (is_null($exception)) ? $arResult["LIKES"] : null;
$isLiked = (is_null($exception)) ? $arResult["IS_LIKED"] : null;
$isLikedCss = ($isLiked) ? "uk-button-primary" : "";
$isLikedTooltip = ($isLiked) ? "Убрать из отложенных" : "Отложить";

$actionValue = ($isLiked) ? DataWrapper::GLOBAL_CATEGORY_LIKE_ACTION_REMOVE
    : DataWrapper::GLOBAL_CATEGORY_LIKE_ACTION_ADD;
?>

<?if(!is_null($exception)):?>
    <?$APPLICATION->IncludeComponent("oip:system.exception.viewer","",[
        "EXCEPTION" => $exception
    ])?>
<?else:?>
    <form action="" method="post">
        <input type="hidden" name="<?=DataWrapper::GLOBAL_CATEGORY_LIKE_ACTION_NAME?>" value="<?=$actionValue?>">
        <input type="hidden" name="<?=DataWrapper::GLOBAL_CATEGORY_LIKE_CATEGORY_ID?>"
               value="<?=$component->getParam("SECTION_ID")?>">
        <?=$likes?>&nbsp;<button class="uk-icon-button uk-margin-small-right <?=$isLikedCss?>"
                                 uk-icon="heart" uk-tooltip="<?=$isLikedTooltip?>"></button>
    </form>
<?endif?>
