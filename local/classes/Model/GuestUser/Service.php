<?php


namespace Oip\Model\GuestUser;

use Oip\Model\GuestUser\IdGenerator\IdGeneratorInterface;
use Oip\Model\GuestUser\Repository\RepositoryInterface;

class Service
{
    /** @var RepositoryInterface $repository */
    private $repository;
    /** @var  IdGeneratorInterface $idGenerator */
    private $idGenerator;

    public function __construct(RepositoryInterface $repository, IdGeneratorInterface $idGenerator)
    {
        $this->repository = $repository;
        $this->idGenerator = $idGenerator;
    }

    /**
     * @return null|Entity\User
     */
    public function getUser() {
        $id = (int) $this->repository->getData();

        if($id) {
            return new Entity\User($id);
        }

        $newUser = $this->createUser();
        $this->repository->setData($newUser->getId());

        return $newUser;
    }

    /** @return Entity\User */
    private function createUser() {
       return new Entity\User($this->idGenerator->generateId());
    }

}