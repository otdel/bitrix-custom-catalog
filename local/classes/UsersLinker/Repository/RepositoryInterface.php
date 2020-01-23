<?php


namespace Oip\UsersLinker\Repository;


interface RepositoryInterface
{
    /**
     * @param $guestUserId int
     * @param $authorizedUserId int
     * @return int
     */
    public function addUsersLink(int $guestUserId, int $authorizedUserId): int;

    /**
     * @param $guestUserId int
     * @param $authorizedUserId int
     * @return bool
     */
    public function isLinkExists(int $guestUserId, int $authorizedUserId): bool;
}