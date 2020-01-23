<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

class AddGuestToAuthorizedLinkTable20200122081036076079 extends BitrixMigration
{
    private $tableName = 'oip_guest_to_authorized_links';

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $sql = "CREATE TABLE {$this->tableName} ("
            . "id               INT             NOT NULL    AUTO_INCREMENT, "
            . "guest_id         INT             NOT NULL, "
            . "authorized_id    INT             NOT NULL, "
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
