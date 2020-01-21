<?php

use Oip\ProductFeature\ProductFeature;
use Oip\ProductFeature\ProductFeatureValue;

/** @var array $arResult */

$productsInfo = $arResult["productsInfo"];
/** @var ProductFeature[] $productFeatures */
$productFeatures = $arResult["productFeatures"];
?>

<? foreach ($productsInfo as $productId => $productInfo): ?>

    <h3>Товар ID <?=$productId?></h3>
    <p class="uk-margin-remove">Кастомные характеристики:</p>

    <? /** @var ProductFeatureValue $productFeatureValue */
    foreach ($productInfo["productFeatures"] as $productFeatureValue): ?>

        <li>
            <?=isset($productFeatures[$productFeatureValue->getFeatureCode()]) ?
                $productFeatures[$productFeatureValue->getFeatureCode()]->getName() :
                $productFeatureValue->getFeatureCode() ?> [код хар-ки: <?=$productFeatureValue->getFeatureCode()?>]: <?=$productFeatureValue->getValue()?>
        </li>

    <?endforeach;?>

<? endforeach; ?>