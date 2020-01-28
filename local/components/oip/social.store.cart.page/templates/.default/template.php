<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use CBitrixComponentTemplate;
use COipSocialStoreCartPage;

use Oip\SocialStore\Cart\Handler as Cart;
use  Oip\SocialStore\Product\Entity\ProductCollection;

/** @var array $arResult */
/** @var $this CBitrixComponentTemplate */
/** @var $component COipSocialStoreCartPage */
/** @var $products ProductCollection */
/** @var $cart Cart */

$component = $this->getComponent();


$exception = $arResult["EXCEPTION"];
$errors = $arResult["ERRORS"];
$success = $arResult["SUCCESS"];

if(!$exception) {
    $cart =  $arResult["CART"];
    $products = $cart->getProducts();
}

?>

<div class="uk-container">
    <div class="uk-padding">

        <?if($exception):?>
            <p style="color:red"><?=$exception?></p>
        <?else:?>

            <?if(!empty($errors)):?>
                <?foreach($errors as $error):?>
                    <p style="color:red"><?=$error?></p>
                <?endforeach?>
            <?endif?>

            <?if($success):?>
                <p style="color:green"><?=$success?></p>
            <?endif?>

            <?if($products->isEmpty()):?>
                <table class="uk-table">
                    <caption>Ваша корзина пуста</caption>
                </table>
            <?else:?>

                <table class="uk-table">
                    <caption>В вашей корзине  <?=$products->count()?> <?=$component->getNumWord($products->count(),
                            ["товар","товара","товаров"])?></caption>

                    <thead>
                    <tr>
                        <th>Товар</th>
                        <th>Изображение</th>
                        <th>Описание</th>
                        <th>Дествия</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?foreach($products as $product):?>
                        <tr>

                            <td><?=$product->getName()?></td>
                            <td>
                                <?if($product->getLink()):?>
                                <a href="<?=$product->getLink()?>" title="<?=$product->getName()?>">
                                    <?endif?>

                                    <img src="<?=$product->getPicture()?>" alt="<?=$product->getName()?>" width="150">
                                    <?if($product->getLink()):?>
                                </a>
                            <?endif?>
                            </td>
                            <td><?=$product->getDescription()?></td>
                            <td>
                                <form action="" class="uk-form" method="post">
                                    <input type="hidden" name="<?=$cart::GLOBAL_CART_DATA_PRODUCT_ID?>" value="<?=$product->getId()?>">
                                    <input type="hidden" name="oipCartActionHandler" value="<?=$component->getComponentId()?>">
                                    <button class="uk-button" name="<?=$cart::GLOBAL_CART_ACTION_NAME?>"
                                            value="<?=$cart::GLOBAL_CART_ACTION_REMOVE_PRODUCT?>">
                                        <i class="uk-icon" uk-icon="close" ></i>
                                        Удалить
                                    </button>
                                </form>

                            </td>
                        </tr>
                    <?endforeach?>
                    </tbody>

                    <tfoot>
                    <tr>
                        <td colspan="2">
                            <form action="" class="uk-form" method="post">
                                <input type="hidden" name="oipCartActionHandler" value="<?=$component->getComponentId()?>">
                                <button class="uk-button" name="<?=$cart::GLOBAL_CART_ACTION_NAME?>"
                                        value="<?=$cart::GLOBAL_CART_ACTION_REMOVE_ALL?>">
                                    <i class="uk-icon" uk-icon="trash" ></i>
                                    Очистить корзину
                                </button>
                            </form>
                        </td>
                        <td colspan="2">
                            <form action="" class="uk-form" method="post">
                                <input type="hidden" name="oipCartActionHandler" value="<?=$component->getComponentId()?>">
                                <button class="uk-button uk-button-primary" name="<?=$cart::GLOBAL_CART_ACTION_NAME?>"
                                        value="<?=$cart::GLOBAL_CART_ACTION_CREATE_ORDER?>">
                                    <i class="uk-icon" uk-icon="credit-card" ></i>
                                    Оформить заказ
                                </button>
                            </form>
                        </td>
                    </tr>
                    </tfoot>

                </table>

            <?endif?>
        <?endif?>

    </div>
</div>
