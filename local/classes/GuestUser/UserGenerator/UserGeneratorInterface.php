<?php

namespace Oip\GuestUser\UserGenerator;

use Oip\GuestUser\Entity\User;

interface UserGeneratorInterface
{
    public function generateUser(): User;
    /**
     * @param $hashId string
     * @return User
    */
    public function getUserByHashId(string $hashId): User;
}