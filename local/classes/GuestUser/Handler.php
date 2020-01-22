<?php


namespace Oip\GuestUser;

use Oip\GuestUser\Entity\User;
use Oip\GuestUser\UserGenerator\UserGeneratorInterface;
use Oip\GuestUser\Repository\RepositoryInterface;

class Handler
{
    /** @var RepositoryInterface $repository */
    private $repository;
    /** @var  UserGeneratorInterface $userGenerator */
    private $userGenerator;

    /** @var $user User */
    private $user;

    public function __construct(RepositoryInterface $repository, UserGeneratorInterface $userGenerator)
    {
        $this->repository = $repository;
        $this->userGenerator = $userGenerator;
    }

    /**
     * @return User
     */
    public function getUser(): User {

        if(is_null($this->user))     {
            $this->user = $this->fetchUser();
        }

        return $this->user;
    }

    /**
     * @return User
     */
    private function fetchUser(): User {
        $hashId = $this->repository->getData();

        if(!$hashId) {
            $user = $this->userGenerator->generateUser();
        }
        else {
            $user = $this->userGenerator->getUserByHashId($hashId);
        }

        return $user;
    }

    /**
     * @return void
     */
    public function setUser(): void {
        $this->repository->setData($this->user->getHashId());
    }
}