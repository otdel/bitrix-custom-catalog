<?php

namespace Oip\SocialStore\Order;

use Oip\SocialStore\Order\Entity\Order;
use Oip\SocialStore\Order\Repository\RepositoryInterface as OrderRepository;
use Oip\SocialStore\Order\Status\Repository\RepositoryInterface as StatusRepository;

class Handler
{
    /** @var OrderRepository $orderRepository */
    private $orderRepository;
    /** @var StatusRepository $statusRepository */
    private $statusRepository;

    public function __construct(OrderRepository $orderRepository, StatusRepository $statusRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->statusRepository = $statusRepository;
    }

   public function updateOrderStatus(Order $order, string $statusCode): Order {
        $orderId = $order->getId();
        $statusId = $this->statusRepository->getByCode($statusCode)->getId();

        $this->orderRepository->updateStatus($orderId, $statusId);

        return $this->orderRepository->getById($orderId);
    }
}