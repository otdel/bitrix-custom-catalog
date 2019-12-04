<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class CreateTableOipProductView20191111093833262768 extends BitrixMigration
{

    private $tableName = 'oip_product_view';

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */

    public function up()
    {
        $sql = "CREATE TABLE {$this->tableName} ("
            . "id               INT             NOT NULL AUTO_INCREMENT,"
            . "user_id          INT             NOT NULL,"
            . "product_id       INT             DEFAULT 0,"
            . "section_id       INT             DEFAULT 0,"
            . "date_insert      TIMESTAMP       NOT NULL        DEFAULT CURRENT_TIMESTAMP(),"
            . "date_modify      TIMESTAMP       DEFAULT NULL    DEFAULT CURRENT_TIMESTAMP(),"
            . "views_count      INT             NOT NULL    DEFAULT 0,"
            . "is_liked         TINYINT         NOT NULL    DEFAULT 0,"
            . "PRIMARY KEY (product_view_id)"
            . ");";
        $this->db->query($sql);

        // Создаем индекс на уникальную пару - user_id + product_id + section_id
        $sql = "ALTER TABLE `{$this->tableName}` ADD UNIQUE `idx_product_view_user`(`user_id`, `product_id`, `section_id`);";
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
