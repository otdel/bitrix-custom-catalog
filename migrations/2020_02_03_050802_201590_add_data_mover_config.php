<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Bitrix\Main\Config\Configuration;

class AddDataMoverConfig20200203050802201590 extends BitrixMigration
{
    private $configName = "oip_data_mover";

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        if(!is_set(Configuration::getValue($this->configName))) {
            Configuration::setValue($this->configName, [
                [
                    "entityName" => "oip_carts",
                    "uniqueCols" => ["product_id"]
                ],
                [
                    "entityName" => "oip_product_view",
                    "uniqueCols" => ["product_id", "section_id"]
                ]
            ]);
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
        if(is_set(Configuration::getValue($this->configName))) {
            $config = Configuration::getInstance();
            $config->delete($this->configName);
            $config->saveConfiguration();
        }
    }
}
