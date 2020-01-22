<?php


namespace Oip\GuestUser;

use Oip\GuestUser\Entity\User;
use Oip\GuestUser\IdGenerator\IdGeneratorInterface;
use Oip\GuestUser\Repository\RepositoryInterface;

class Handler
{
    /** @var RepositoryInterface $repository */
    private $repository;
    /** @var  IdGeneratorInterface $idGenerator */
    private $idGenerator;

    /** @var $user User */
    private $user;

    public function __construct(RepositoryInterface $repository, IdGeneratorInterface $idGenerator)
    {
        $this->repository = $repository;
        $this->idGenerator = $idGenerator;
    }

    /**
     * @return User
     */
    public function getUser(): User {

        if(is_null($this->user))     {
            $this->user = new User($this->fetchUserId());
        }

        return $this->user;
    }

    /**
     * @return int
     */
    private function fetchUserId(): int {
        $id = (int)$this->repository->getData();
        if(!$id) {
            $id = $this->idGenerator->generateId();
        }

        return $id;
    }

    /**
     * @return void
     */
    public function setUser(): void {
        $this->repository->setData($this->user->getId());
    }
}