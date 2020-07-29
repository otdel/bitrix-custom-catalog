<?php

namespace Oip\SocialStore\Order\Entity;

use DateTime;
use Oip\SocialStore\Product\Entity\Product;
use \Oip\SocialStore\User\Entity\User;
use \Oip\SocialStore\Order\Status\Entity\Status;
use \Oip\SocialStore\Product\Entity\ProductCollection;

class Order
{
    /** @var int|null $id */
    private $id;
    /** @var DateTime $created */
    private $created;
    /** @var User $user */
    private $user;
    /** @var Status $status */
    private $status;
    /** @var ProductCollection $products */
    private $products;

    /**
     * @param int|null $id
     * @param DateTime|null $created
     * @param User $user
     * @param Status $status
     * @param ProductCollection $products
     */
    public function __construct(User $user, Status $status, ProductCollection $products, ?int $id = null,
                                ?DateTime $created = null)
    {
        $this->id = $id;
        $this->created = $created;
        $this->user = $user;
        $this->status = $status;
        $this->products = $products;
    }


    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return DateTime|null
     */
    public function getCreated(): ?DateTime
    {
        return $this->created;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return Status
     */
    public function getStatus(): Status
    {
        return $this->status;
    }

    /**
     * @return ProductCollection
     */
    public function getProducts(): ProductCollection
    {
        return $this->products;
    }

    /** @return float */
    public function getTotalPrice() {
        $totalPrice = 0;
        /** @var Product $product */
        foreach($this->products as $product) {
            $totalPrice += $product->getPrice();
        }

        return $totalPrice;
    }
}