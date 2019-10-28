<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Arrilot\BitrixMigrations\Logger;


class AddTagsIblockProps20191021143657839362 extends BitrixMigration
{
    public function up()
    {
        try {

            $ibID = $this->getIblockIdByCode("oip_tags");

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

            $this->addIblockElementProperty([
                "NAME" => "Показывать в каталоге",
                "SORT" => 400,
                "CODE" => "SHOW_IN_CATALOG",
                "IBLOCK_ID" => $ibID,
                "PROPERTY_TYPE" => "L",
                "LIST_TYPE" => "C",
                "VALUES" => [
                    0 => [
                        "VALUE" => "Да",
                        "SORT" => 100,
                    ]
                ]
            ]);
            Logger::log("Свойство SHOW_IN_CATALOG создано",
                Logger::COLOR_LIGHT_GREEN);
        }
        catch(MigrationException $e) {
            Logger::log($e->getMessage(),
                Logger::COLOR_LIGHT_RED);
        }
    }

    public function down()
    {
        try{
            $iblockId = $this->getIblockIdByCode("oip_tags");

            $this->deleteIblockElementPropertyByCode($iblockId, "SHOW_IN_CATALOG");
            Logger::log("Свойство SHOW_IN_CATALOG удалено",
                Logger::COLOR_LIGHT_GREEN);

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
