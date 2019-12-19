<?php

namespace Oip\SocialStore\Cart;

use Oip\SocialStore\Product\Entity;
use Oip\SocialStore\User\Entity\User;
use Oip\SocialStore\Cart\Repository\RepositoryInterface;

class Handler
{

    const GLOBAL_CART_ACTION_NAME = "oipCartAction";
    const GLOBAL_CART_ACTION_REMOVE_PRODUCT = "oipCartRemoveProduct";
    const GLOBAL_CART_ACTION_ADD_PRODUCT = "oipCartAddProduct";
    const GLOBAL_CART_ACTION_REMOVE_ALL = "oipCartRemoveAll";
    const GLOBAL_CART_DATA_PRODUCT_ID = "oipCartProductId";

    /** @var User $user */
    private $user;
    /** @var Entity\ProductCollection $products */
    private $products;
    /** @var RepositoryInterface $repository */
    private $repository;

    /**
     * @param User $user
     * @param Entity\ProductCollection $products
     * @param RepositoryInterface $repository
     */
    public function __construct(
        User $user,
        Entity\ProductCollection $products,
        RepositoryInterface $repository
    )
    {
        $this->user = $user;
        $this->products = $products;
        $this->repository = $repository;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return Entity\ProductCollection
     */
    public function getProducts(): Entity\ProductCollection
    {
        if($this->products->isEmpty()) {
            $this->products = $this->repository->getByUserId($this->user->getId());
        }
        return $this->products;
    }

    /**
     * @param int $productId
     * @return  self
     */
    public function addProduct(int $productId): self {
        $this->products = $this->repository->addFlush($this->user->getId(), $productId);
        return $this;
    }

    /**
     * @param int $productId
     * @return  self
     */
    public function removeProduct(int $productId): self {
        $this->products = $this->repository->removeFlush($this->user->getId(), $productId);
        return $this;
    }

    /**
     * @return  self
     */
    public function removeAll(): self {
        $this->products = $this->repository->removeAllFlush($this->user->getId());
        return $this;
    }
}