<?php


namespace Oip\GuestUser;

use Oip\GuestUser\Entity\User;
use Oip\GuestUser\IdGenerator\IdGeneratorInterface;
use Oip\GuestUser\Repository\RepositoryInterface;

use Oip\RelevantProducts\DBDataSource as RelevantProductsDS;
use Oip\CacheInfo;

class Handler
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
     * @return null|User
     */
    public function getUser() {
        $id = (int) $this->repository->getData();

        if($id) {
            return new User($id);
        }

        $newUser = $this->createUser();
        $this->repository->setData($newUser->getId());

        return $newUser;
    }

    /** @returnUser */
    private function createUser() {
       return new User($this->idGenerator->generateId());
    }

    /** @return RelevantProductsDS */
    public static function getIDGenDataSource(): RelevantProductsDS {
        global $DB;

        $cacheInfo = new CacheInfo();
        return new RelevantProductsDS($DB, $cacheInfo);
    }
}