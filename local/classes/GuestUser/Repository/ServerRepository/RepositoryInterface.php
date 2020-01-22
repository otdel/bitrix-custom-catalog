<?php

namespace Oip\GuestUser\Repository\ServerRepository;

use Oip\GuestUser\Entity\User;

interface RepositoryInterface
{
    public function addUser(): User;
    /**
     * @param $hashId string
     * @return User
    */
    public function getUserByHashId(string $hashId): User;
}