<?php


namespace Oip\SocialStore\Product\Price;


class StubPriceProvider implements PriceProviderInterface
{
    /** @param array $prices */
    private $prices;

    public function __construct(array $prices)
    {
        $this->prices = $prices;
    }

    public function buildPrices(): PriceCollection
    {
        $prices = [];

        foreach ($this->prices as $price) {
            if($price["product_id"] && $price["price"]) {
                $prices[] = new Price((int)$price["product_id"], (float)$price["price"]);
            }
        }

        return new PriceCollection($prices);
    }

}