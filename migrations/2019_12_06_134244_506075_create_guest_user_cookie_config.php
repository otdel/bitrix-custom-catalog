<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Bitrix\Main\Config\Configuration;

class CreateGuestUserCookieConfig20191206134244506075 extends BitrixMigration
{
    private $configName = "oip_guest_user";

    public function up()
    {
        $configValue = Configuration::getValue($this->configName);
        if (!isset($configValue["lifetime"])) {
            $configValue["cookieName"] = "OIP_GUEST_ID";
            $configValue["cookieExpired"] = 30;
        }
        Configuration::setValue($this->configName, $configValue);
    }

    public function down()
    {
        $config = Configuration::getInstance();
        $config->delete($this->configName);
        $config->saveConfiguration();
    }
}
