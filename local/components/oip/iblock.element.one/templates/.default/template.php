<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/** @var $this \CBitrixComponentTemplate */
/** @var $component \COipIblockElementOne */
/** @var array $arResult */
/** @var \Oip\Custom\Component\Iblock\Element $element */

$exception = $arResult["EXCEPTION"];
$errors = $arResult["ERRORS"];
$element = $arResult["ELEMENT"];
$component = $this->getComponent();

$quantityProp = $component->getParam("QUANTITY_PROP");
$quantity = ($quantityProp) ?  (int)$element->getPropValue($quantityProp) : null;

$WAREID = null;
$COLOR = null;
$res = CIBlockElement::GetProperty($element->getIblockId(), $element->getId(), "sort", "asc", array("CODE" => "WARE_ID"));
if ($ob = $res->GetNext()) {
    $WAREID = $ob['VALUE'];
}

?>
<div class="uk-section uk-section-default">

    <div class="uk-container uk-container-large">

        <?if($exception):?>
            <p><?=$exception?></p>
        <?else:?>

            <?if($errors):?>
                <?foreach($errors as $error):?>
                    <p><?=$error?></p>
                <?endforeach?>
            <?endif?>

            <?if($element):?>

                <div class="uk-grid-large uk-grid-match" uk-grid>
                    <div class="uk-width-3-5@m">
                        <?include_once (__DIR__."/include/slider.php")?>
                    </div>

                    <div class="uk-width-2-5@m">
                        <div class="uk-panel">

                            <?if($element->getPropValue("BRANDS")):?>
                                <div class="uk-text-uppercase uk-text-primary uk-text-bold uk-margin-small-bottom">
                                    <?include_once (__DIR__."/include/brands.php")?>
                                </div>
                            <?endif?>

                            <h1 class="uk-h3 uk-margin-remove-bottom">
                                <?=$element->getName()?>
                                <?if($element->getPropValue("BADGE")):?>
                                    <ins class="uk-margin-small-left uk-text-small">
                                        <sup><?=$element->getPropValue("BADGE")?></sup>
                                        </ins>
                                <?endif?>

                            </h1>

                            <?if($element->getSectionName()):?>
                                <p class="uk-margin-small-top uk-margin-medium-bottom  uk-text-small">
                                    <a class="uk-link-reset uk-display-block" href=""><?=$element->getSectionName()?></a>
                                </p>
                            <?endif?>

                            <?if(!is_null($quantity)):?>
                                <p>Количество: <?=$quantity?></p>
                            <?endif?>

                            <p class="uk-margin-medium-top uk-text-large uk-text-nowrap price-detail">15 555&nbsp;₽</p>
                            <?$APPLICATION->IncludeComponent("oip:product_features","", [
                                    "IBLOCK_ID" => (int)$element->getIblockId(),
                                    "ELEMENT_ID" => (int)$element->getId(),
                            ])?>

                            <p>
                                <?=$element->getPreviewText()?>
                            </p>

                            <div class="uk-grid-small uk-child-width-auto uk-flex-middle" uk-grid>
                               <?if($quantityProp && !$quantity):?>
                                    <button class="uk-button uk-disabled">Недоступен к заказу</button>
                                    <?// скрываем кнопку при заданном свойстве колиечества товара и 0-вом значении свойства?>
                               <?else:?>
                                    <?// показываем корзину при ненулевом количестве или в режиме без количества ?>
                                   <div>
                                       <?$APPLICATION->IncludeComponent("oip:social.store.cart.button","",[
                                           "PRODUCT_ID" => $element->getId(),
                                           "BUTTON_ICON_ADD" => "cart",
                                           "BUTTON_ICON_REMOVE" => "close"
                                       ])?>
                                   </div>
                               <?endif?>
                                <div>
                                    <?$APPLICATION->IncludeComponent("oip:relevant.products.likes.product.widget","",[
                                        "PRODUCT_ID" => $element->getId()
                                    ]);?>
                                </div>
                                <div>
                                    <?$APPLICATION->IncludeComponent("oip:relevant.products.views.product.count","",[
                                        "PRODUCT_ID" => $element->getId()
                                    ]);?>
                                </div>
                            </div>
                            
                            <?include_once (__DIR__."/include/bottom.php")?>

                            <?include_once (__DIR__."/include/tags.php")?>

                        </div>
                    </div>

                </div>

                <div 
                    id="react-product-properties" 
                    data-productid="<?=$element->getId()?>" 
                    data-iblockid="<?=$element->getIblockId()?>"
                    data-wareid="<?=$WAREID?>"
                >
                </div>


                <div class="uk-margin-large-top">
                    <?=$element->getDetailText()?>
                </div>

                <?if($component->isParam("CARD_VIEW_SHOW_SIDEBAR")):?>
                    <div class="uk-margin-large-top">
                        Блок сайбдара в деталке
                    </div>
                <?endif?>

                <?if($component->isParam("CARD_VIEW_SHOW_SAME_ELEMENT")):?>
                    <div class="uk-margin-large-top">
                        Блок "Похожие товары"
                    </div>
                <?endif?>

                <?if($component->isParam("CARD_VIEW_SHOW_POPULAR_WITH_THIS")):?>
                    <div class="uk-margin-large-top">
                        Блок "С этими товарами покупают"
                    </div>
                <?endif?>
            <?endif?>

        <?endif?>

    </div>
</div>
