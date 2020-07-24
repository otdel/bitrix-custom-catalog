<?php


namespace Oip\SocialStore\Product\Price;


class StubPriceProvider implements PriceProviderInterface
{
    public function buildPrices(): PriceCollection
    {
        $prices = [
            new Price(1000,100),
            new Price(1002,200),
            new Price(1003,300),
            new Price(1004,400),
            new Price(1005,500),
        ];

        return new PriceCollection($prices);
    }

}