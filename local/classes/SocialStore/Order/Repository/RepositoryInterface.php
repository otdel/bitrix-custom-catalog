<?php

namespace Oip\SocialStore\Order\Repository;

use Oip\SocialStore\Order\Entity;

interface RepositoryInterface
{

    /**
     * @param int $orderId
     * @return Entity\Order
     */
    public function getById(int $orderId): Entity\Order;

    /**
     * @param int $userId
     * @param int $page
     * @param int|null $onPage
     * @return Entity\OrderCollection
     */
    public function getAllByUserId(int $userId, $page = 1, $onPage = null): Entity\OrderCollection;

    /**
     * @param int
     * @return int
    */
    public function getCountByUserId(int $userId): int;

    /**
     * @param Entity\Order $order
     * @return int
     * */
    public function addOrder(Entity\Order $order): int;

    /**
     * @param int $orderId
     * @return int
    */
    public function removeOrder(int $orderId): int;

    /**
     * @param int $orderId
     * @param int $statusId
     * @return void
     */
    public function updateStatus(int $orderId, int $statusId): void;
}