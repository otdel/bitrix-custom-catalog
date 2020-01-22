<?php

namespace Oip\SocialStore\Order\Status\Repository;

use Bitrix\Main;
use Oip\SocialStore\Order\Status\Entity;

class DBRepository implements RepositoryInterface
{
    /** @var string $statusesTableName */
    private $statusesTableName = "oip_order_statuses";
    /** @var Main\DB\Connection $db */
    private $db;

    /** @param Main\DB\Connection $connection */
    public function __construct(Main\DB\Connection $connection)
    {
        $this->db = $connection;
    }

    /**
     * @inheritdoc
     * @throws Main\DB\SqlQueryException
     */
    public function getByCode(string $statusCode): Entity\Status
    {
        $sql = $this->getByCodeSql($statusCode);
        $status = $this->db->query($sql)->fetch();

        return new Entity\Status($status["id"], $status["code"], $status["label"]);
    }

    /**
     * @param string $statusCode
     * @return string
     */
    private function getByCodeSql(string $statusCode): string {
        return "SELECT * FROM {$this->statusesTableName} WHERE code = '$statusCode' ";
    }

}