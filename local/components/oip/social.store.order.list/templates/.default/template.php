<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Oip\SocialStore\Order\Entity\OrderCollection;
use Oip\SocialStore\Order\Entity\Order;
use Oip\SocialStore\Product\Entity\Product;

/** @var array $arResult */
/** @var $this CBitrixComponentTemplate */
/** @var $component COipSocialStoreOrderList */
/** @var $orders OrderCollection */
/** @var $order Order */
/** @var $product Product */

$component = $this->getComponent();
$orders =  $arResult["ORDERS"];
$count = $arResult["COUNT"];
$arResult["EXCEPTION"];
?>


<div class="uk-container">
    <div class="uk-padding">

    <?if($arResult["EXCEPTION"]):?>
        <p  style="color:red"><?=$arResult["EXCEPTION"]?></p>
    <?else:?>

        <?if(!$count):?>
            <p>У вас нет заказов</p>
        <?else:?>

        <table class="uk-table uk-table-divider uk-table-striped">
            <caption>У вас <?=$count?> <?=$component->getNumWord($count,
                    ["заказ","заказа","заказов"])?></caption>

            <thead>
                <tr>
                    <th>Заказ</th>
                    <th>Состав заказа</th>
                    <th>Cтоимость</th>
                    <th>Статус заказа</th>
                    <th>Дата заказа</th>
                </tr>
            </thead>

            <tbody>

                <?foreach($orders as $order):?>

                    <tr>
                        <td><?=$order->getId()?></td>
                        <td>
                            <?foreach($order->getProducts() as $key => $product):?>

                                <?if($key):?>
                                    <hr>
                                <?endif?>

                                <div>
                                    <?if($product->getPicture()):?>
                                        <img width="150" src="<?=$product->getPicture()?>" alt="<?=$product->getName()?>">
                                    <?endif?>
                                    
                                    <h6 class="uk-margin-small-top"><?=$product->getName()?></h6>

                                    <?if($product->getArticle()):?>
                                        <div>
                                            Артикул:
                                            <?=$product->getArticle()?>
                                        </div>
                                    <?endif?>

                                    <?if($product->getPrice()):?>
                                        <div>
                                            Цена: <?=number_format($product->getPrice(),0, ".", " ")?> ₽
                                        </div>
                                    <?endif?>

                                </div>
                            <?endforeach?>
                        </td>
                        <td>
                            <?if($order->getTotalPrice()):?>
                                <div>
                                    <?=number_format($order->getTotalPrice(),0, ".", " ")?> ₽
                                </div>
                            <?endif?>
                        </td>
                        <td><div class="uk-badge"><?=$order->getStatus()->getLabel()?></div></td>
                        <td><?=$order->getCreated()->format("d.m.Y H:i")?></td>
                    </tr>

                <?endforeach?>

            </tbody>
        </table>

        <?endif?>

    <?endif?>

    </div>
</div>
