<?php

namespace Oip\SocialStore\Product\Entity;

use Oip\Util\Collection;

class ProductCollection extends Collection
{

    /** @param Product[] $products */
    public function __construct(array $products)
    {
        $this->values = $products;
    }

    /**
     * @param int $productId
     * @return Product|null
     */
    public function getById($productId): ?Product {
        $result = reset(array_filter($this->values, function($product) use ($productId) {
            /** @var Product $product */
            return ($product->getId() === $productId);
        }));

        return ($result) ? $result : null;
    }

    /** @return bool */
    public function isEmpty(): bool {
        return (!$this->count());
    }

}