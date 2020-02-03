<?php

namespace Oip\GuestUser\Repository\ServerRepository;

use DateTime;
use Exception;

use Bitrix\Main\DB\SqlException;
use Bitrix\Main\DB\Connection;

use Oip\GuestUser\Entity\User;
use Oip\GuestUser\Repository\ServerRepository\Exception\AddingNewGuest as AddingNewGuestException;
use Oip\GuestUser\Repository\ServerRepository\Exception\GettingByHashId as GettingByHashIdException;

class DBRepository implements RepositoryInterface
{

    /** @var $guestUserTableName string */
    private $guestUserTableName = "oip_guest_users";

    /** @var $db Connection */
    private $db;

    public function __construct(Connection $connection)
    {
        $this->db = $connection;
    }

    /**
     * @inheritDoc
     * @throws SqlException;
     * @throws GettingByHashIdException;
    */
    public  function getUserByHashId(string $hashId): User {
        $user = $this->db->query("SELECT * FROM {$this->guestUserTableName} WHERE `hash_id` = '$hashId' ")->fetch();
        if(!$user) {
            throw new GettingByHashIdException($hashId);
        }
        return new User((int)$user["id"], $user["hash_id"]);
    }

    /**
     * @return User
     * @throws SqlException
     * @throws AddingNewGuestException
     * @throws Exception
     */
   public function addUser(): User
   {
       $hashId = $this->generateHashId();

       $this->db->query("INSERT INTO {$this->guestUserTableName} (hash_id) VALUE ('$hashId') ");

       if($this->db->getAffectedRowsCount() === 0) {
           throw new AddingNewGuestException();
       }

       return (new User($this->db->getInsertedId(), $hashId));
   }

    /** @return string
     * @throws Exception
     */
   private function generateHashId(): string {
       return hash("md5", ((new DateTime())->getTimestamp()));
   }
}