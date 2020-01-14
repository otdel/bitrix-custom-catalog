<?php

namespace Oip\SocialStore\Order\Status\Repository;

use Oip\SocialStore\Order\Status\Entity;

interface RepositoryInterface
{
    /**
     * @param string $statusCode
     * @return Entity\Status
     */
    public function getByCode(string $statusCode): Entity\Status;
}