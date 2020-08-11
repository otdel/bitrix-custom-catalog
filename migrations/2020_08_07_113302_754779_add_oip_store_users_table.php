<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class AddOipStoreUsersTable20200807113302754779 extends BitrixMigration
{
    private $tableName = "oip_store_users";

    /**
     * Run the migration.
     *
     * @return void
     * @throws \Exception
     */
    public function up()
    {
        $sql = "CREATE TABLE {$this->tableName} ("
            . "id               INT             NOT NULL    AUTO_INCREMENT, "
            . "bx_id            INT             NOT NULL, "
            . "email            VARCHAR(255)    NOT NULL, "
            . "phone            VARCHAR(255)    NOT NULL, "
            . "name             VARCHAR(255)    NOT NULL, "
            . "surname          VARCHAR(255), "
            . "patronymic       VARCHAR(255), "
            . "PRIMARY KEY (id)"
            . ");";
        $this->db->query($sql);
    }

    /**
     * Reverse the migration.
     *
     * @return void
     * @throws \Exception
     */
    public function down()
    {
        $this->db->dropTable($this->tableName);
    }
}
