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

        <form action="" class="uk-form" method="post">
        <?
        if($inCart){
            $cartAction = $cart::GLOBAL_CART_ACTION_REMOVE_PRODUCT;
            $buttonText = $component->getParam("BUTTON_TEXT_REMOVE");
            $buttonIcon = $component->getParam("BUTTON_ICON_REMOVE");;
        }
        else {
            $cartAction = $cart::GLOBAL_CART_ACTION_ADD_PRODUCT;
            $buttonText = $component->getParam("BUTTON_TEXT_ADD");
            $buttonIcon = $component->getParam("BUTTON_ICON_ADD");;
        }
        ?>

        <input type="hidden" name="<?=$cart::GLOBAL_CART_DATA_PRODUCT_ID?>" value="<?=$component->getParam("PRODUCT_ID")?>">
        <input type="hidden" name="oipCartActionHandler" value="<?=$component->getComponentId()?>">
        <button class="uk-button uk-button-primary"
                name="<?=$cart::GLOBAL_CART_ACTION_NAME?>"
                value="<?=$cartAction?>"
        >
            <?if($buttonIcon):?>
                <i class="uk-icon uk-margin-small-right" uk-icon="<?=$buttonIcon?>"></i><?=$buttonText?>
            <?endif?>
        </button>
        </form>
    </p>
<?endif?>

