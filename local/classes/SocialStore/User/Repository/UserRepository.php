<?php


namespace Oip\SocialStore\User\Repository;

use Bitrix\Main\DB\Result;
use Oip\SocialStore\User\UseCase\Register\Request\Command as RegisterCommand;
use Oip\SocialStore\User\Entity\User;

use Bitrix\Main\DB\Connection;
use Bitrix\Main\Db\SqlQueryException;

class UserRepository implements UserRepositoryInterface
{
    /** @var string $storeUserTable */
    private $storeUserTable = "oip_store_users";

    /** @var Connection $db */
    private $db;

    public function __construct(Connection $db) {
        $this->db = $db;
    }

    /**
     * @param int $id
     * @return User
     * @throws SqlQueryException
     */
    public function getById($id): User {
        $res = $this->db->query("SELECT * FROM {$this->storeUserTable} WHERE id = $id");
        $users = $this->parseRow($res);
        return reset($users);
    }

    /**
     * @param string $phone
     * @return User[]
     * @throws SqlQueryException
     */
    public function getByPhone($phone): array {
        $res = $this->db->query("SELECT * FROM {$this->storeUserTable} WHERE phone = $phone");
        return $this->parseRow($res);
    }

    /**
     * @param string $email
     * @return User[]
     * @throws SqlQueryException
     */
    public function getByEmail($email): array {
        $res = $this->db->query("SELECT * FROM {$this->storeUserTable} WHERE phone = $email");
        return $this->parseRow($res);
    }

    /**
     * @param Result $res
     * @return User[]
     */
    private function parseRow(Result $res) {
        $rows = $res->fetchAll();

        return array_map(function ($userRow) {
            return new User(
                (int)$userRow["id"],
                $userRow["email"],
                $userRow["phone"],
                (int)$userRow["bx_id"],
                $userRow["name"],
                $userRow["surname"],
                $userRow["patronymic"],
            );
        }, $rows);
    }

    /**
     * @param RegisterCommand $command
     * @param int $bxUserId
     * @return int
     * @throws SqlQueryException
     */
    public function add(RegisterCommand $command, int $bxUserId) {
        $sql = $this->addSql($command, $bxUserId);

        $this->db->query($sql);

        return (int)$this->db->getInsertedId();
    }

    /**
     * @param int $userId
     * @param string $code
     * @return int
     * @throws SqlQueryException
     */
    public function addVerification(int $userId, string $code) {
        $this->db->query("UPDATE {$this->storeUserTable} SET"
            ." phone_verification_code = '$code',"
            ." phone_verification_code_expired = DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 1 HOUR)"
            ." WHERE id = $userId");

        return $this->db->getAffectedRowsCount();
    }

    /**
     * @param int $userId
     * @return int
     * @throws SqlQueryException
     */
    public function verifyUserPhone(int $userId)
    {
       $this->db->query("UPDATE {$this->storeUserTable} "
           ." SET phone_verified = 1, phone_verification_code = NULL, phone_verification_code_expired = NULL"
           ." WHERE id = $userId ");


       return $this->db->getAffectedRowsCount();
    }


    /**
     * @param RegisterCommand $command
     * @param int $bxUserId
     * @return string
     */
    private function addSql(RegisterCommand $command, int $bxUserId) {
        return "INSERT INTO {$this->storeUserTable} (bx_id, email, phone, name, surname, patronymic) ".
            " VALUES ($bxUserId, '{$command->email}', '{$command->phone}', '{$command->name}',"
            ." '{$command->surname}', '{$command->patronymic}')";
    }
}