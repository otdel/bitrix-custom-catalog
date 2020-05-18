<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Oip\RelevantProducts\DataWrapper;

/** @var array $arResult */
/** @var Exception $exception */
/** @var CBitrixComponentTemplate $this  */
/** @var CRelevantProductsLikesProductWidget $component  */

$component = $this->getComponent();
$exception = $arResult["EXCEPTION"];
$likes = (is_null($exception)) ? $arResult["LIKES"] : null;
$isLiked = (is_null($exception)) ? $arResult["IS_LIKED"] : null;

$actionValue = ($isLiked) ? DataWrapper::GLOBAL_PRODUCT_LIKE_ACTION_REMOVE
    : DataWrapper::GLOBAL_PRODUCT_LIKE_ACTION_ADD;
?>

<?if(!is_null($exception)):?>
    <?$APPLICATION->IncludeComponent("oip:system.exception.viewer","",[
        "EXCEPTION" => $exception
    ])?>
<?else:?>
    <form action="" method="post" onsubmit="return false">
        <div
            class="react-like-button"
            data-productid="<?=$component->getParam("PRODUCT_ID")?>"
            data-isliked="<?=$isLiked === true ? "true" : "false";?>"
            data-likes="<?=$likes === null ? "0" : $likes ?>"
            data-iscategory="false"
        >
        </div>
    </form>
<?endif?>
