<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class AlterSectionFeatureSortInfoColumn20200130130844885776 extends BitrixMigration
{
    private $tableName = 'oip_section_feature';

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */

    public function up()
    {
        $sql = "ALTER TABLE `{$this->tableName}` " .
               "CHANGE COLUMN `sort_info` `sort_info` INT(11) NULL DEFAULT '100' COMMENT 'Порядок вывода характеристики в информации о товаре' AFTER `sort_filter`;";
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
        //
    }
}
