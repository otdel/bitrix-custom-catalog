<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class AddHashidToGuests20200121102231807548 extends BitrixMigration
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
        $sql = "ALTER TABLE `{$this->tableName}` ADD COLUMN `hash_id` VARCHAR(255) NOT NULL";
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
        $sql = "ALTER TABLE `{$this->tableName}` DROP COLUMN `hash_id`";
        $this->db->query($sql);
    }
}
