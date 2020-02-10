<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class AddLastVisitColToGuestUsers20200210074950772913 extends BitrixMigration
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
        $sql = "ALTER TABLE `{$this->tableName}` ADD COLUMN `last_visit` TIMESTAMP";
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
        $sql = "ALTER TABLE `{$this->tableName}` DROP COLUMN `last_visit`";
        $this->db->query($sql);
    }
}
