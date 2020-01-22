<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class AddOipGuestUserTable20200117065930799288 extends BitrixMigration
{
    private $tableName = 'oip_guest_users';

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */

    public function up()
    {
        $sql = "CREATE TABLE {$this->tableName} ("
            . "id               INT             NOT NULL        AUTO_INCREMENT, "
            . "date_insert      TIMESTAMP       NOT NULL        DEFAULT CURRENT_TIMESTAMP(), "
            . "date_modify      TIMESTAMP, "
            . "PRIMARY KEY (id)"
            . ");";
        $this->db->query($sql);
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        $this->db->dropTable($this->tableName);
    }
}
