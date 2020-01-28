<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Bitrix\Main\Config\Configuration;

class ResetGuestCookieDefaultTime20200128054205616979 extends BitrixMigration
{

    private $configName = "oip_guest_user";
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $configValue = Configuration::getValue($this->configName);
        $configValue["cookieExpired"] = 7776000; // 3 months

        Configuration::setValue($this->configName, $configValue);
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        $configValue = Configuration::getValue($this->configName);
        $configValue["cookieExpired"] = 30;

        Configuration::setValue($this->configName, $configValue);
    }
}
