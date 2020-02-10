<?php

namespace Oip\GuestUser\Repository\ServerRepository;

use Bitrix\Main\DB\SqlQueryException;
use DateTime;
use Exception;

use Bitrix\Main\DB\SqlException;
use Bitrix\Main\DB\Connection;
use Bitrix\Main\Config\Configuration;

use Oip\GuestUser\Entity\User;
use Oip\GuestUser\Repository\ServerRepository\Exception\AddingNewGuest as AddingNewGuestException;
use Oip\GuestUser\Repository\ServerRepository\Exception\GettingByHashId as GettingByHashIdException;

use Oip\Util\Bitrix\DateTimeConverter;

class DBRepository implements RepositoryInterface
{
    const LAST_VISIT_TIME_CONFIG_NAME = "oip_last_visit_time_minutes";

    /** @var $guestUserTableName string */
    private $guestUserTableName = "oip_guest_users";

    /** @var $db Connection */
    private $db;

    /** @var DateTimeConverter $converter */
    private $converter;

    public function __construct(Connection $connection, DateTimeConverter $dateTimeConverter)
    {
        $this->db = $connection;
        $this->converter = $dateTimeConverter;
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

        if(is_null($user["last_visit"])) {
            $lastVisit = new DateTime();
            $this->updateLastVisit((int)$user["id"], $lastVisit);
        }
        else {
            $lastVisit = $this->converter->convertBitrixToNative($user["last_visit"]);

            if($this->isLastVisitExpired($lastVisit)) {
                $lastVisit = new DateTime();
                $this->updateLastVisit((int)$user["id"], $lastVisit);
            }
        }

        return new User((int)$user["id"], $user["hash_id"], $lastVisit);
    }

    /**
     * @param DateTime $lastVisit
     * @return bool
     * @throws Exception
     */
    private function isLastVisitExpired(DateTime $lastVisit): bool {
        $currentDateTimestamp = (new DateTime())->getTimestamp();
        $lastVisitTimestamp = $lastVisit->getTimestamp();

        $minutes = (int)Configuration::getValue(self::LAST_VISIT_TIME_CONFIG_NAME);

        return (($currentDateTimestamp - $lastVisitTimestamp)/60 >= $minutes) ? true : false;
    }

    /**
     * @param int $userId
     * @param DateTime $lastVisit
     * @return int
     *
     * @throws SqlQueryException
     */
    private function updateLastVisit(int $userId, DateTime $lastVisit): int {
        $this->db->query("UPDATE {$this->guestUserTableName} "
            ."SET `last_visit` = '{$lastVisit->format('Y-m-d H:i:s')}' WHERE `id` = $userId");
        return $this->db->getAffectedRowsCount();
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

       return (new User($this->db->getInsertedId(), $hashId, new DateTime()));
   }

    /** @return string
     * @throws Exception
     */
   private function generateHashId(): string {
       return hash("md5", ((new DateTime())->getTimestamp()));
   }
}