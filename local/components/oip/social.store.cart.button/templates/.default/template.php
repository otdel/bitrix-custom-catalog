<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/** @var array $arResult */
/** @var $this \CBitrixComponentTemplate */
/** @var $cart \Oip\SocialStore\Cart\Handler */

$exception = $arResult["EXCEPTION"];
$cart = $arResult["CART"];
$inCart = $arResult["IN_CART"];
?>

<?if($exception):?>
    <p><?=$exception?></p>
<?else:?>
    <p class="uk-margin-medium-top uk-text-center">
        <form action="" class="uk-form" method="post" onsubmit="return false">
            <div 
                class="react-add-to-cart-button"
                data-productid="<?=$component->getParam("PRODUCT_ID")?>"
                data-incart="<?=$inCart === true ? "true" : "false";?>"
            ></div>
        </form>
    </p>
<?endif?>

