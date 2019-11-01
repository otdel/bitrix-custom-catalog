<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Arrilot\BitrixMigrations\Logger;

class AddReviewsIblockProps20191021145612112900 extends BitrixMigration
{
    public function up()
    {
        try {

            $ibID = $this->getIblockIdByCode("oip_reviews");

            $this->addIblockElementProperty([
                "NAME" => "Промодерировано",
                "SORT" => 100,
                "CODE" => "MODERATED",
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
            Logger::log("Свойство MODERATED создано",
                Logger::COLOR_LIGHT_GREEN);

            $this->addIblockElementProperty([
                "NAME" => "Официальный ответ",
                "SORT" => 200,
                "CODE" => "ANSWER",
                "IBLOCK_ID" => $ibID,
                "USER_TYPE" => "HTML",
                "WITH_DESCRIPTION" => "Y",
            ]);
            Logger::log("Свойство ANSWER создано",
                Logger::COLOR_LIGHT_GREEN);

            $this->addIblockElementProperty([
                "NAME" => "Рейтинг",
                "SORT" => 300,
                "CODE" => "RATING",
                "PROPERTY_TYPE" => "N",
                "COL_COUNT" => 3,
                "IBLOCK_ID" => $ibID,
            ]);
            Logger::log("Свойство RATING создано",
                Logger::COLOR_LIGHT_GREEN);

            $this->addIblockElementProperty([
                "NAME" => "Title",
                "SORT" => 400,
                "CODE" => "TITLE",
                "IBLOCK_ID" => $ibID,
            ]);
            Logger::log("Свойство TITLE создано",
                Logger::COLOR_LIGHT_GREEN);

            $this->addIblockElementProperty([
                "NAME" => "Description",
                "SORT" => 500,
                "CODE" => "DESCRIPTION",
                "IBLOCK_ID" => $ibID,
            ]);
            Logger::log("Свойство DESCRIPTION создано",
                Logger::COLOR_LIGHT_GREEN);

            $this->addIblockElementProperty([
                "NAME" => "Keywords",
                "SORT" => 600,
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
        try{
            $iblockId = $this->getIblockIdByCode("oip_reviews");

            $this->deleteIblockElementPropertyByCode($iblockId, "KEYWORDS");
            Logger::log("Свойство KEYWORDS удалено",
                Logger::COLOR_LIGHT_GREEN);

            $this->deleteIblockElementPropertyByCode($iblockId, "DESCRIPTION");
            Logger::log("Свойство DESCRIPTION удалено",
                Logger::COLOR_LIGHT_GREEN);

            $this->deleteIblockElementPropertyByCode($iblockId, "TITLE");
            Logger::log("Свойство TITLE удалено",
                Logger::COLOR_LIGHT_GREEN);

            $this->deleteIblockElementPropertyByCode($iblockId, "RATING");
            Logger::log("Свойство RATING удалено",
                Logger::COLOR_LIGHT_GREEN);

            $this->deleteIblockElementPropertyByCode($iblockId, "ANSWER");
            Logger::log("Свойство ANSWER удалено",
                Logger::COLOR_LIGHT_GREEN);

            $this->deleteIblockElementPropertyByCode($iblockId, "MODERATED");
            Logger::log("Свойство MODERATED удалено",
                Logger::COLOR_LIGHT_GREEN);
        }
        catch(MigrationException $e) {
            Logger::log($e->getMessage(),
                Logger::COLOR_LIGHT_RED);
        }
    }
}
