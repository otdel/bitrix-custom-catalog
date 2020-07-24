<?php

namespace Oip\SocialStore\Product\Price;

use Oip\Util\Collection;

class PriceCollection extends Collection
{
    /** @param Price[] $prices */
    public function __construct(array $prices)
    {
        $this->values = $prices;
    }

    /**
     * @param int $productId
     * @return Price|null
     */
    public function getByProductId($productId): ?Price {
        $result = reset(array_filter($this->values, function($price) use ($productId) {
            /** @var Price $price */
            return ($price->getProductId() === $productId);
        }));

        return ($result) ? $result : null;
    }
}