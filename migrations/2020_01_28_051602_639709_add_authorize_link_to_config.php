<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Bitrix\Main\Config\Configuration;

class AddAuthorizeLinkToConfig20200128051602639709 extends BitrixMigration
{

    private $configName = "oip_authorize_link";

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        Configuration::setValue($this->configName, "/auth/");
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        $config = Configuration::getInstance();
        $config->delete($this->configName);
        $config->saveConfiguration();
    }
}
