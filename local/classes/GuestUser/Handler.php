<?php


namespace Oip\GuestUser;

use Oip\GuestUser\Entity\User;
use Oip\GuestUser\Repository\ClientRepository\Exception\UserDoesntExist;
use Oip\GuestUser\Repository\ServerRepository\RepositoryInterface as ServerRepositoryInterface;
use Oip\GuestUser\Repository\ClientRepository\RepositoryInterface as ClientRepositoryInterface;

use Oip\GuestUser\Repository\ClientRepository\Exception\UserDoesntExist as UserDoesntExistException;
use Oip\GuestUser\Repository\ServerRepository\Exception\GettingByHashId as GettingByHashIdException;

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
            try {
                $user = $this->serverRepository->getUserByHashId($hashId);
            }
            catch (GettingByHashIdException $exception) {
                $user = $this->serverRepository->addUser();
            }

        }

        return $user;
    }

    /**
     * @return void
     * @throws UserDoesntExistException
     */
    public function setUser(): void {
        if(is_null($this->user)) {
            throw new UserDoesntExistException();
        }
        else {
            $this->clientRepository->setData($this->user->getHashId());
        }
    }
}