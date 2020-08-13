<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class AddPhoneVerificationFields20200811151531918070 extends BitrixMigration
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
        $this->db->query("ALTER TABLE {$this->tableName}  ADD COLUMN phone_verified TINYINT NOT NULL DEFAULT 0");
        $this->db->query("ALTER TABLE {$this->tableName}  ADD COLUMN phone_verification_code INT");
        $this->db->query("ALTER TABLE {$this->tableName}  ADD COLUMN phone_verification_code_expired TIMESTAMP");
    }

    /**
     * Reverse the migration.
     *
     * @return void
     * @throws \Exception
     */
    public function down()
    {
        $this->db->query("ALTER TABLE {$this->tableName}  DROP COLUMN phone_verification_code_expired");
        $this->db->query("ALTER TABLE {$this->tableName}  DROP COLUMN phone_verification_code");
        $this->db->query("ALTER TABLE {$this->tableName}  DROP COLUMN phone_verified");
    }
}
