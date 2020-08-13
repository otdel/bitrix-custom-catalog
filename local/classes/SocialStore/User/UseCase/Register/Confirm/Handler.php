<?php

namespace Oip\SocialStore\User\UseCase\Register\Confirm;

use Oip\SocialStore\User\Repository\UserRepositoryInterface;
use DateTimeImmutable;

class Handler
{
    /** @var UserRepositoryInterface $repository */
    private $repository;

    public function __construct(UserRepositoryInterface $repository) {
        $this->repository = $repository;
    }

    /**
     * @param Command $command
     */
    public function handle(Command $command) {

        $verifyingUser = $this->repository->getById($command->userId);

        $verifyingUser->checkVerification($command->verificationCode, new DateTimeImmutable());

        $this->repository->verifyUserPhone($command->userId);
    }
}