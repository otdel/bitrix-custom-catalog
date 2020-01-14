<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

class CreateOipOrderStatusTable20200109144604308898 extends BitrixMigration
{
    private $tableName = 'oip_order_statuses';

    public function up()
    {
        $sql = "CREATE TABLE `{$this->tableName}` ("
            . "id               INT                     NOT NULL AUTO_INCREMENT, "
            . "code             VARCHAR(255)            NOT NULL, "
            . "label            VARCHAR(255)            NOT NULL, "
            . "PRIMARY KEY (id), "
            . "UNIQUE(code)"
            . ");";
        $this->db->query($sql);
    }


    public function down()
    {
        $this->db->dropTable($this->tableName);
    }
}
