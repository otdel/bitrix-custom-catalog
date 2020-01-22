<?php

namespace Oip\SocialStore\Cart\Repository;

use Oip\SocialStore\Product\Entity;

interface RepositoryInterface
{
    /**
     * @param int $userId
     * @return Entity\ProductCollection
     */
    public function getByUserId($userId): Entity\ProductCollection;

    /**
     * @param int $userId
     * @param int $productId
     * @return Entity\ProductCollection
     */
    public function addFlush(int $userId, int $productId): Entity\ProductCollection;

    /**
     * @param int $userId
     * @param int $productId
     * @return Entity\ProductCollection
     */
    public function removeFlush(int $userId, int $productId): Entity\ProductCollection;

    /**
     * @param int $userId
     * @return Entity\ProductCollection
     */
    public function removeAllFlush(int $userId): Entity\ProductCollection;
}