<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Arrilot\BitrixMigrations\Logger;

class CreateProductFeatureTables20200121113730487875 extends BitrixMigration
{
    private $elementFeatureTableName = 'oip_element_feature';
    private $elementFeaturePredefinedTableName = 'oip_element_feature_predefined_value';
    private $elementFeatureValueTableName = 'oip_element_feature_value';

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */

    public function up()
    {
        try {
            $sql = "CREATE TABLE `{$this->elementFeatureTableName}` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT,
                    `code` VARCHAR(128) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
                    `name` VARCHAR(128) NOT NULL COMMENT 'Название характеристики (заголовок)' COLLATE 'utf8_unicode_ci',
                    `sort_filter` INT(11) NOT NULL DEFAULT '0' COMMENT 'Порядок сортировки в списке фильтров',
                    `sort_info` INT(11) NOT NULL DEFAULT '0' COMMENT 'Порядок сортировки в информации о товаре (например, в деталке)',
                    `css_filter_classname` VARCHAR(64) NULL DEFAULT NULL COMMENT 'Имя CSS класса для вывода в списке фильтров' COLLATE 'utf8_unicode_ci',
                    `is_filter` TINYINT(4) NOT NULL DEFAULT '0' COMMENT 'Возможно ли сортировать по данному полю? 0 - Нет, 1 - Можно',
                    `is_predefined` TINYINT(4) NOT NULL DEFAULT '1' COMMENT 'Является ли значение поля одним из ранее предустановленых или можно ввести вручную',
                    `is_disabled` TINYINT(4) NOT NULL DEFAULT '0' COMMENT 'Признак отключения (мягкого удаления) характеристики',
                    `date_insert` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Дата добавления записи в таблицу',
                    `date_modify` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Дата последнего изменения записи',
                    PRIMARY KEY (`id`),
                    UNIQUE INDEX `code` (`code`)
                )
                COMMENT='Таблица со всеми существующими характеристиками (Объем, диагональ и т.д.)\r\n'
                COLLATE='utf8_unicode_ci'; ";
            $this->db->query($sql);
            Logger::log("Таблица {$this->elementFeatureTableName} создана", Logger::COLOR_LIGHT_GREEN);
        }
        catch (Exception $ex) {
            Logger::log($ex->getMessage(), Logger::COLOR_LIGHT_RED);
        }

        try {
            $sql = "CREATE TABLE `{$this->elementFeaturePredefinedTableName}` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT,
                    `value` VARCHAR(128) NOT NULL COMMENT 'Значение' COLLATE 'utf8_unicode_ci',
                    `date_insert` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Дата добавления записи в таблицу',
                    `date_modify` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Дата последнего изменения записи',
                    `is_disabled` TINYINT(4) NOT NULL DEFAULT '0' COMMENT 'Признак отключения (мягкого удаления)',
                    PRIMARY KEY (`id`)
                )
                COMMENT='Таблица со всеми предустановленными значениями характеристик (Объем - 10, 20, 30 литров; Диагональ - 55, 50, 40 дюймов; и т.д.)'
                COLLATE='utf8_unicode_ci'; ";
            $this->db->query($sql);
            Logger::log("Таблица {$this->elementFeaturePredefinedTableName} создана", Logger::COLOR_LIGHT_GREEN);
        }
        catch (Exception $ex) {
            Logger::log($ex->getMessage(), Logger::COLOR_LIGHT_RED);
        }

        try {
            $sql = "CREATE TABLE `{$this->elementFeatureValueTableName}` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT,
                    `element_id` INT(11) NOT NULL COMMENT 'Идентификатор товара (элемента инфоблока)',
                    `feature_code` VARCHAR(50) NOT NULL DEFAULT '' COMMENT 'Код характеристики' COLLATE 'utf8_unicode_ci',
                    `predefined_value_id` VARCHAR(128) NOT NULL COMMENT 'Значение из списка предустановленных' COLLATE 'utf8_unicode_ci',
                    `value` VARCHAR(128) NOT NULL COMMENT 'Значение (заполненное вручную)' COLLATE 'utf8_unicode_ci',
                    `is_disabled` TINYINT(4) NOT NULL DEFAULT '0' COMMENT 'Признак отключения (мягкого удаления)',
                    `date_insert` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Дата добавления записи в таблицу',
                    `date_modify` TIMESTAMP NULL DEFAULT NULL COMMENT 'Дата последнего изменения записи',
                    PRIMARY KEY (`id`)
                )
                COMMENT='Таблица со значениями характеристик у товаров. (Соответствие b_iblock_element_id записям из oip_element_feature_predefined_value; либо установка значения вручную в поле value данной таблицы)'
                COLLATE='utf8_unicode_ci'; ";
            $this->db->query($sql);
            Logger::log("Таблица {$this->elementFeatureValueTableName} создана", Logger::COLOR_LIGHT_GREEN);
        }
        catch (Exception $ex) {
            Logger::log($ex->getMessage(), Logger::COLOR_LIGHT_RED);
        }
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        try {
            $this->db->dropTable($this->elementFeatureTableName);
        }
        catch (Exception $ex) {
            Logger::log($ex->getMessage(), Logger::COLOR_LIGHT_RED);
        }

        try {
            $this->db->dropTable($this->elementFeaturePredefinedTableName);
        }
        catch (Exception $ex) {
            Logger::log($ex->getMessage(), Logger::COLOR_LIGHT_RED);
        }

        try {
            $this->db->dropTable($this->elementFeatureValueTableName);
        }
        catch (Exception $ex) {
            Logger::log($ex->getMessage(), Logger::COLOR_LIGHT_RED);
        }
    }
}
