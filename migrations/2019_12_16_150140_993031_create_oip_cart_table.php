<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

class CreateOipCartTable20191216150140993031 extends BitrixMigration
{
    private $tableName = 'oip_carts';

    public function up()
    {
        $sql = "CREATE TABLE `{$this->tableName}` ("
                . "id               INT            NOT NULL AUTO_INCREMENT, "
                . "user_id          INT            NOT NULL, "
                . "product_id       INT            NOT NULL, "
                . "date_create      TIMESTAMP      NOT NULL   DEFAULT CURRENT_TIMESTAMP(),"
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
