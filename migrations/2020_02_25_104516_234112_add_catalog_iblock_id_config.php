<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Bitrix\Main\Config\Configuration;

class AddCatalogIblockIdConfig20200225104516234112 extends BitrixMigration
{
    private $configName = "oip_catalog_iblock_id";

    public function up()
    {
        if(!is_set(Configuration::getValue($this->configName))) {
            Configuration::setValue($this->configName, "");
        }
    }

    public function down()
    {
        if(is_set(Configuration::getValue($this->configName))) {
            $config = Configuration::getInstance();
            $config->delete($this->configName);
            $config->saveConfiguration();
        }
    }
}
