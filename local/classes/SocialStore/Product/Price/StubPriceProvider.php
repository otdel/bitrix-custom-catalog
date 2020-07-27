<?php

namespace Oip\SocialStore\Product\Price;

class StubPriceProvider implements PriceProviderInterface
{
    /** @param array $prices */
    private $prices;

    /**
     * @param int $productId
     * @param float|null $price
     */
    public function addProduct(int $productId, float $price = null) {
        $this->prices[] = [
            "product_id" => $productId,
            "price" => ($price) ?? (float)rand(100,9999) // провайдер-заглушка генерит случайные значения цен
        ];
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