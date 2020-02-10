<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Bitrix\Main\Config\Configuration;

class AddLastVisitTimeConfig20200210092244293044 extends BitrixMigration
{
    private $configName = "oip_last_visit_time_minutes";

    public function up()
    {
        Configuration::setValue($this->configName, "5");
    }

    public function down()
    {
        $config = Configuration::getInstance();
        $config->delete($this->configName);
        $config->saveConfiguration();
    }
}
