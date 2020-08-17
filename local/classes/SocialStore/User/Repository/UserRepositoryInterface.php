<?php

namespace Oip\SocialStore\User\Repository;

use Oip\SocialStore\User\Entity\User;
use Oip\SocialStore\User\UseCase\Register\Request\Command as RegisterCommand;

interface UserRepositoryInterface
{
    /**
     * @param int $id
     * @throws NotFoundException
     * @return User
     */
    public function getById(int $id): User;

    /**
     * @param string $phone
     * @throws NotFoundException
     * @return User[]
     */
    public function getByPhone($phone) : array;

    /**
     * @param string $phone
     * @throws NotFoundException
     * @return User[]
     */
    public function getByEmail($phone) : array;

    /**
     * @param RegisterCommand $command
     * @param int $bxUserId
     * @return int
     */
    public function add(RegisterCommand $command, int $bxUserId);

    /**
     * @param int $userId
     * @param string $code
     * @return int
     */
    public function addVerification(int $userId, string $code);

    /**
     * @param int $userId
     * @return int
     */
    public function verifyUserPhone(int $userId);
}