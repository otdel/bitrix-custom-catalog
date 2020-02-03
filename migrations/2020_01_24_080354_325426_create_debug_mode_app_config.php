<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Bitrix\Main\Config\Configuration;

class CreateDebugModeAppConfig20200124080354325426 extends BitrixMigration
{
    private $configName = "oip_debug_mode";

    public function up()
    {
        Configuration::setValue($this->configName, "N");
    }

    public function down()
    {
        $config = Configuration::getInstance();
        $config->delete($this->configName);
        $config->saveConfiguration();
    }
}
