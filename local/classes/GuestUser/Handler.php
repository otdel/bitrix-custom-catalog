<?php


namespace Oip\GuestUser;

use Oip\GuestUser\Entity\User;
use Oip\GuestUser\Repository\ServerRepository\RepositoryInterface as ServerRepositoryInterface;
use Oip\GuestUser\Repository\ClientRepository\RepositoryInterface as ClientRepositoryInterface;

class Handler
{
    /** @var ClientRepositoryInterface $clientRepository */
    private $clientRepository;
    /** @var  ServerRepositoryInterface $serverRepository */
    private $serverRepository;

    /** @var $user User */
    private $user;

    public function __construct(ClientRepositoryInterface $clientRepository, ServerRepositoryInterface $serverRepository)
    {
        $this->clientRepository = $clientRepository;
        $this->serverRepository = $serverRepository;
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
        $hashId = $this->clientRepository->getData();

        if(!$hashId) {
            $user = $this->serverRepository->addUser();
        }
        else {
            $user = $this->serverRepository->getUserByHashId($hashId);
        }

        return $user;
    }

    /**
     * @return void
     */
    public function setUser(): void {
        $this->clientRepository->setData($this->user->getHashId());
    }
}