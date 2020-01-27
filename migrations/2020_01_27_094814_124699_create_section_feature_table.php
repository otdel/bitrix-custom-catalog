<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class CreateSectionFeatureTable20200127094814124699 extends BitrixMigration
{
    private $tableName = "oip_section_feature";

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */

    public function up()
    {
        $sql = "CREATE TABLE `{$this->tableName}` ( " .
                " `id` INT(11) NOT NULL AUTO_INCREMENT, " .
                " `section_id` INT(11) NULL DEFAULT NULL, " .
                " `feature_code` VARCHAR(50) NULL DEFAULT NULL COMMENT 'Код характеристики' COLLATE 'utf8_unicode_ci', " .
                " `is_filter` TINYINT(4) NULL DEFAULT '0' COMMENT 'Флаг - можно ли фильтровать товары в категории по данной характеристике', " .
                " `is_info` TINYINT(4) NULL DEFAULT NULL COMMENT 'Флаг - отображать в информации о товаре', " .
                " `is_disabled` TINYINT(4) NULL DEFAULT '0' COMMENT 'Признак отключения (мягкого удаления)', " .
                " `sort_filter` INT(11) NULL DEFAULT '100' COMMENT 'Порядок вывода характеристики при выводе ее в форме фильтров ', " .
                " `sort_info` INT(11) NULL DEFAULT NULL COMMENT 'Порядок вывода характеристики в информации о товаре', " .
                " `date_insert` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Дата добавления записи в таблицу', " .
                " `date_modify` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Дата последнего изменения записи', " .
                " PRIMARY KEY (`id`) " .
            ");";
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
