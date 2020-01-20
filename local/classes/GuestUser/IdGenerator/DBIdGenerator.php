<?php

namespace Oip\GuestUser\IdGenerator;

use Bitrix\Main\DB\SqlException;
use Bitrix\Main\DB\Connection;

use Oip\GuestUser\IdGenerator\Exception\AddingNewGuestId as AddingNewGuestIdException;

class DBIdGenerator implements IdGeneratorInterface
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
     * @return int
     * @throws SqlException
     * @throws AddingNewGuestIdException
     */
   public function generateId(): int
   {
       $this->db->query("INSERT INTO {$this->guestUserTableName} (id) VALUE ('') ");

       if($this->db->getAffectedRowsCount() === 0) {
           throw new AddingNewGuestIdException();
       }
       return (0 - $this->db->getInsertedId());
   }
}