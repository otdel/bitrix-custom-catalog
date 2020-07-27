<?php

namespace Oip\SocialStore\Product\Price;

interface PriceProviderInterface
{
    /** @param int $productId */
    public function addProduct(int $productId);
    /** @return PriceCollection */
    public function buildPrices(): PriceCollection;
}