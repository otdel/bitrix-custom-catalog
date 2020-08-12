<?php

namespace Oip\SocialStore\Cart;

use Oip\SocialStore\Product\Entity;
use Oip\SocialStore\Product\Price\PriceProviderInterface;
use Oip\SocialStore\Cart\Repository\RepositoryInterface;

class Handler
{

    const GLOBAL_CART_ACTION_NAME = "oipCartAction";
    const GLOBAL_CART_ACTION_REMOVE_PRODUCT = "oipCartRemoveProduct";
    const GLOBAL_CART_ACTION_ADD_PRODUCT = "oipCartAddProduct";
    const GLOBAL_CART_ACTION_REMOVE_ALL = "oipCartRemoveAll";
    const GLOBAL_CART_ACTION_CREATE_ORDER = "oipCartCreateOrder";
    const GLOBAL_CART_DATA_PRODUCT_ID = "oipCartProductId";

    /** @var int $userId */
    private $userId;
    /** @var Entity\ProductCollection $products */
    private $products;
    /** @var RepositoryInterface $repository */
    private $repository;
    /** @var PriceProviderInterface $priceProvider */
    private $priceProvider;

    /**
     * @param int $userId
     * @param Entity\ProductCollection $products
     * @param RepositoryInterface $repository
     * @param PriceProviderInterface $priceProvider
     */
    public function __construct(
        int $userId,
        Entity\ProductCollection $products,
        RepositoryInterface $repository,
        PriceProviderInterface $priceProvider
    )
    {
        $this->userId = $userId;
        $this->products = $products;
        $this->repository = $repository;
        $this->priceProvider = $priceProvider;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @return Entity\ProductCollection
     */
    public function getProducts(): Entity\ProductCollection
    {
        if($this->products->isEmpty()) {
            $this->products = $this->repository->getByUserId($this->userId);
            $this->fillPrices();
        }
        return $this->products;
    }

    /* @return void */
    public function fillPrices() {
        /** @var Entity\Product $product */

        foreach($this->products as $product) {
            $this->priceProvider->addProduct($product->getId());
        }

        $prices = $this->priceProvider->buildPrices();


        foreach($this->products as $product) {

            if($price = $prices->getByProductId($product->getId())) {
                $product->setPrice($price->getValue());
            }
        }
    }

    /**
     * @param int $productId
     * @return bool
     */
    public function hasProduct(int $productId): bool {
        return ($this->products->getById($productId)) ? true : false;
    }

    /**
     * @param int $productId
     * @return  self
     */
    public function addProduct(int $productId): self {
        $this->products = $this->repository->addFlush($this->userId, $productId);
        return $this;
    }

    /**
     * @param int $productId
     * @return  self
     */
    public function removeProduct(int $productId): self {
        $this->products = $this->repository->removeFlush($this->userId, $productId);
        return $this;
    }

    /**
     * @return  self
     */
    public function removeAll(): self {
        $this->products = $this->repository->removeAllFlush($this->userId);
        return $this;
    }
}