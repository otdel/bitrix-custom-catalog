<?php


namespace Oip\SocialStore\User\Repository;

use Bitrix\Main\DB\Result;
use Oip\SocialStore\User\UseCase\Register\Request\Command as RegisterCommand;
use Oip\SocialStore\User\Entity\User;

use Bitrix\Main\DB\Connection;
use Bitrix\Main\Db\SqlQueryException;
use Oip\Util\Bitrix\DateTimeConverter;
use Exception;

class UserRepository implements UserRepositoryInterface
{
    /** @var string $storeUserTable */
    private $storeUserTable = "oip_store_users";

    /** @var Connection $db */
    private $db;

    /** @var DateTimeConverter $converter */
    private $converter;

    public function __construct(Connection $db, DateTimeConverter $converter) {
        $this->db = $db;
        $this->converter = $converter;
    }

    /**
     * @param int $id
     * @return User
     * @throws SqlQueryException
     * @throws NotFoundException
     * @throws Exception
     */
    public function getById(int $id): User {
        $res = $this->db->query("SELECT * FROM {$this->storeUserTable} WHERE id = $id");
        $users = $this->parseRow($res);
        return reset($users);
    }

    /**
     * @param int $bxId
     * @return User
     * @throws SqlQueryException
     * @throws NotFoundException
     * @throws DuplicateFoundException
     * @throws Exception
     */
    public function getByBxId(int $bxId): User {
        $res = $this->db->query("SELECT * FROM {$this->storeUserTable} WHERE bx_id = $bxId");

        $users = $this->parseRow($res);

        if(count($users) > 1) {
            throw new DuplicateFoundException("По данному bxId = $bxId обнаружены дубликаты клиентов");
        }

        return reset($users);
    }

    /**
     * @param string $phone
     * @return User
     * @throws SqlQueryException
     */
    public function getByPhone($phone): User
    {
        $res = $this->db->query("SELECT * FROM {$this->storeUserTable} WHERE phone = $phone");
        $users = $this->parseRow($res);
        return reset($users);
    }

    /**
     * @param string $email
     * @return User
     * @throws SqlQueryException
     */
    public function getByEmail($email): User {
        $res = $this->db->query("SELECT * FROM {$this->storeUserTable} WHERE phone = $email");
        $users = $this->parseRow($res);
        return reset($users);
    }

    /**
     * @param Result $res
     * @throws NotFoundException
     * @throws Exception
     * @return User[]
     */
    private function parseRow(Result $res) {
        $rows = $res->fetchAll();

        if(empty($rows)) {
            throw new NotFoundException("По данному запросу не найдено пользователей");
        }

        return array_map(function ($userRow) {

            $dateExpired = null;
            if($userRow["phone_verification_code_expired"]) {
                $dateExpired = $this->converter->convertBitrixToImmutable($userRow["phone_verification_code_expired"]);
            }

            return new User(
                (int)$userRow["id"],
                $userRow["email"],
                $userRow["phone"],
                (int)$userRow["bx_id"],
                (int)$userRow["phone_verified"],
                $userRow["name"],
                $userRow["surname"],
                $userRow["patronymic"],
                $userRow["phone_verification_code"],
                $dateExpired
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