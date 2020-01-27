<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class AlterElementFeatureTable20200122113212448057 extends BitrixMigration
{
    private $tableName = 'oip_element_feature';

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */

    public function up()
    {
        $sql = "ALTER TABLE {$this->tableName}
                DROP COLUMN `sort_filter`,
                DROP COLUMN `sort_info`,
                DROP COLUMN `is_filter`;";
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
    }
}
