<?php


namespace Oip\DataMover\Repository;

use Bitrix\Main\DB\Connection;
use Bitrix\Main\DB\SqlQueryException;

class DBRepository implements RepositoryInterface
{

    /** @var $db Connection */
    private $db;

    public function __construct(Connection $connection)
    {
        $this->db = $connection;
    }

    /**
     * @inheritDoc
     * @throws SqlQueryException
     */
    public function getRecords(string $entityName, int $guestId, int $userId): array
    {
        $sql = "SELECT * FROM $entityName WHERE user_id IN($guestId, $userId)";
        return $this->db->query($sql)->fetchAll();
    }

    /**
     * @inheritDoc
     * @throws SqlQueryException
     */
    public function updateNonDuplicateRecords(string $entityName, array $arRecords, int $guestId, int $userId): int {
        $records = implode(",", $arRecords);
        $sql = "UPDATE $entityName SET `user_id` = $userId, "
            ."`date_modify` = NOW() WHERE `user_id` = $guestId AND `id` IN($records) ";
        $this->db->query($sql);

        return $this->db->getAffectedRowsCount();
    }

    /**
     * @inheritDoc
     * @throws SqlQueryException
     */
    public function executeQuery(string $query)
    {
        return $this->db->query($query);
    }
}