<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/** @var $arResult array */
/** @var $count int */

$exception = $arResult["EXCEPTION"];
$count = $arResult["COUNT"];
?>

<?if($arResult["EXCEPTION"]):?>
    <p  style="color:red"><?=$arResult["EXCEPTION"]?></p>
<?else:?>
    <?if(!$count):?>
        <div class="uk-padding">
            Корзина пуста
        </div>
    <?else:?>
        <div class="uk-padding">
            <?=$count?> <?=$component->getNumWord($count,
                ["товар","товара","товаров"])?>
        </div>
    <?endif?>

<?endif?>




