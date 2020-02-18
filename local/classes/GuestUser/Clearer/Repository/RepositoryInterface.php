<?php

namespace Oip\GuestUser\Clearer\Repository;

use Oip\GuestUser\Entity\User;
use Oip\GuestUser\Clearer\Entity\ProductView\Record as ProductView;

interface RepositoryInterface {

    /**
     * @return array
     */
    public function getAllGuestId(): array;
    /**
     * @param int $guestId
     * @return User|null
     */
    public function getUserById(int $guestId): ?User;

    /**
     * @param int $guestId
     * @return ProductView[]
     */
    public function getUserProductViews(int $guestId): array;

    /**
     * @param ProductView $record
     * @return int
     */
    public function archiveProductViewRecord(ProductView $record);

    /**
     * @param int $guestId
     * @return int
     */
    public function dropUser(int $guestId);
}