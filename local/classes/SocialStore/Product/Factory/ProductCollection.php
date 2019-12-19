<?php

namespace Oip\SocialStore\Product\Factory;

use  Oip\SocialStore\Product\Entity;
use Oip\SocialStore\Product\Exception\InvalidObjectType;
use Oip\SocialStore\Product\Exception\NonUniqueIdCreating;

class ProductCollection
{
    /**
     * @param Entity\Product[] $products
     * @return Entity\ProductCollection
     * @throws
     */
    public static function createByObjects(array $products): Entity\ProductCollection {

        $ids = [];

        foreach($products as $product) {

            if(!$product instanceof Entity\Product) {
                throw new InvalidObjectType(get_class($product), "Oip\SocialStore\Entity\Product");
            }

            if(in_array($product->getId(), $ids)) {
                throw new NonUniqueIdCreating($product->getId(), "Oip\SocialStore\Entity\ProductCollection");
            }

            $ids[] =  $product->getId();
        }

        return new Entity\ProductCollection($products);
    }
}