<?php

namespace Oip\SocialStore\Product\Entity;

class ProductCollection implements \Countable
{
    /** @var Product[] $products */
    private $products;

    public function __construct(array $products)
    {
        $this->products = $products;
    }

    /** @return int */
    public function count(): int
    {
        return count($this->products);
    }

    /** @return Product[] Product */
    public function getArray(): array {
        return $this->products;
    }

    /**
     * @param int $productId
     * @return Product|null
     */
    public function getById($productId): ?Product {
        $result = reset(array_filter($this->products, function($product) use ($productId) {
            /** @var Product $product */
            return ($product->getId() === $productId);
        }));

        return ($result) ? $result : null;
    }

}