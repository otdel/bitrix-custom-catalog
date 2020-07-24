<?php

namespace Oip\SocialStore\Product\Price;

interface PriceProviderInterface
{
    /** @return PriceCollection */
    public function buildPrices(): PriceCollection;
}