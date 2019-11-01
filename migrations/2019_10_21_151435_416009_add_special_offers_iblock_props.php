<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Arrilot\BitrixMigrations\Logger;

class AddSpecialOffersIblockProps20191021151435416009 extends BitrixMigration
{
    public function up()
    {
        try {

            $ibID = $this->getIblockIdByCode("oip_special_offers");

            $this->addIblockElementProperty([
                "NAME" => "Title",
                "SORT" => 100,
                "CODE" => "TITLE",
                "IBLOCK_ID" => $ibID,
            ]);
            Logger::log("Свойство TITLE создано",
                Logger::COLOR_LIGHT_GREEN);

            $this->addIblockElementProperty([
                "NAME" => "Description",
                "SORT" => 200,
                "CODE" => "DESCRIPTION",
                "IBLOCK_ID" => $ibID,
            ]);
            Logger::log("Свойство DESCRIPTION создано",
                Logger::COLOR_LIGHT_GREEN);

            $this->addIblockElementProperty([
                "NAME" => "Keywords",
                "SORT" => 300,
                "CODE" => "KEYWORDS",
                "IBLOCK_ID" => $ibID,
            ]);
            Logger::log("Свойство KEYWORDS создано",
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
            $iblockId = $this->getIblockIdByCode("oip_special_offers");

            $this->deleteIblockElementPropertyByCode($iblockId, "KEYWORDS");
            Logger::log("Свойство KEYWORDS удалено",
                Logger::COLOR_LIGHT_GREEN);

            $this->deleteIblockElementPropertyByCode($iblockId, "DESCRIPTION");
            Logger::log("Свойство DESCRIPTION удалено",
                Logger::COLOR_LIGHT_GREEN);

            $this->deleteIblockElementPropertyByCode($iblockId, "TITLE");
            Logger::log("Свойство TITLE удалено",
                Logger::COLOR_LIGHT_GREEN);
        }
        catch(MigrationException $e) {
            Logger::log($e->getMessage(),
                Logger::COLOR_LIGHT_RED);
        }
    }
}
