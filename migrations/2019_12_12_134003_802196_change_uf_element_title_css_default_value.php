<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Arrilot\BitrixMigrations\Logger;

class ChangeUfElementTitleCssDefaultValue20191212134003802196 extends BitrixMigration
{
    public function up()
    {
        try {
            $ibID = $this->getIblockIdByCode("oip_products");
            $ufId = $this->getUFIdByCode("IBLOCK_{$ibID}_SECTION","UF_ELEMENT_TITLE_CSS");

            $fields = [
                "SETTINGS" => [
                    "DEFAULT_VALUE" => "uk-h1",
                ]
            ];

            $oUserTypeEntity = new CUserTypeEntity();
            $oUserTypeEntity->Update($ufId, $fields);

            Logger::log("поле UF_ELEMENT_TITLE_CSS изменено",
                Logger::COLOR_LIGHT_GREEN);
        }
        catch(MigrationException $e) {
            Logger::log($e->getMessage(),
                Logger::COLOR_LIGHT_RED);
        }
    }

    public function down()
    {
        try {
            $ibID = $this->getIblockIdByCode("oip_products");
            $ufId = $this->getUFIdByCode("IBLOCK_{$ibID}_SECTION","UF_ELEMENT_TITLE_CSS");

            $fields = [
                "SETTINGS" => [
                    "DEFAULT_VALUE" => "uk-h5",
                ]
            ];

            $oUserTypeEntity = new CUserTypeEntity();
            $oUserTypeEntity->Update($ufId, $fields);

            Logger::log("поле UF_ELEMENT_TITLE_CSS изменено",
                Logger::COLOR_LIGHT_GREEN);
        }
        catch(MigrationException $e) {
            Logger::log($e->getMessage(),
                Logger::COLOR_LIGHT_RED);
        }
    }
}
