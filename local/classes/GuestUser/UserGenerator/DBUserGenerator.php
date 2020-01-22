<?php

namespace Oip\GuestUser\UserGenerator;

use DateTime;
use Exception;

use Bitrix\Main\DB\SqlException;
use Bitrix\Main\DB\Connection;

use Oip\GuestUser\Entity\User;
use Oip\GuestUser\Exception\AddingNewGuestId as AddingNewGuestIdException;

class DBUserGenerator implements UserGeneratorInterface
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
    */
    public  function getUserByHashId(string $hashId): User {
        $user = $this->db->query("SELECT * FROM {$this->guestUserTableName} WHERE `hash_id` = '$hashId' ")->fetch();
        return new User($this->getNegativeId((int)$user["id"]), $user["hash_id"]);
    }

    /**
     * @return User
     * @throws SqlException
     * @throws AddingNewGuestIdException
     * @throws Exception
     */
   public function generateUser(): User
   {
       $hashId = $this->generateHashId();

       $this->db->query("INSERT INTO {$this->guestUserTableName} (hash_id) VALUE ('$hashId') ");

       if($this->db->getAffectedRowsCount() === 0) {
           throw new AddingNewGuestIdException();
       }

       return (new User($this->getNegativeId($this->db->getInsertedId()), $hashId));
   }

    /** @return string
     * @throws Exception
     */
   private function generateHashId(): string {
       return hash("md5", ((new DateTime())->getTimestamp()));
   }

   private function getNegativeId(int $id): int {
       return (0 - $id);
   }
}