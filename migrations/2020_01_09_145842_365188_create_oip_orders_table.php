<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class CreateOipOrdersTable20200109145842365188 extends BitrixMigration
{
    private $tableName = 'oip_orders';

    public function up()
    {
        $sql = "CREATE TABLE `{$this->tableName}` ("
            . "id               INT            NOT NULL AUTO_INCREMENT, "
            . "user_id          INT            NOT NULL, "
            . "status_id        INT   NOT NULL DEFAULT 1, "
            . "products         TEXT           NOT NULL, "
            . "date_create      TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP(),"
            . "date_modify      TIMESTAMP, "
            . "PRIMARY KEY (id)"
            . ");";

        $this->db->query($sql);
    }


    public function down()
    {
        $this->db->dropTable($this->tableName);
    }
}
