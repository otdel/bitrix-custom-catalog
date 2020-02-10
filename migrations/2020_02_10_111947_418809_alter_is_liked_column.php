<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class AlterIsLikedColumn20200210111947418809 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $sql = "ALTER TABLE `oip_product_view` " .
               "CHANGE COLUMN `is_liked` `likes_count` INT NOT NULL DEFAULT 0 AFTER `views_count`;";
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
        $sql = "ALTER TABLE `oip_product_view` " .
            "CHANGE COLUMN `likes_count` `is_liked` TINYINT NOT NULL DEFAULT 0 AFTER `views_count`;";
        $this->db->query($sql);
    }
}
