<?php

namespace Oip\SocialStore\Order\Entity;

use Oip\Util\Collection\Collection;

class OrderCollection extends Collection
{

    /** @param Order[] $orders */
    public function __construct(array $orders)
    {
        $this->values = $orders;
    }

    /**
     * @param int $orderId
     * @return Order|null
     */
    public function getById($orderId): ?Order {
        $result = reset(array_filter($this->values, function($order) use ($orderId) {
            /** @var Order $order */
            return ($order->getId() === $orderId);
        }));

        return ($result) ? $result : null;
    }
}