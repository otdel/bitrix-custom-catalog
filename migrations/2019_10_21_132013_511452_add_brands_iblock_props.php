<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Arrilot\BitrixMigrations\Logger;

class AddBrandsIblockProps20191021132013511452 extends BitrixMigration
{
    public function up()
    {
        try {

            $ibID = $this->getIblockIdByCode("oip_brands");

            $this->addIblockElementProperty([
                "NAME" => "Галерея",
                "SORT" => 100,
                "CODE" => "GALLERY",
                "PROPERTY_TYPE" => "F",
                "FILE_TYPE" => "jpg, gif, bmp, png, jpeg",
                "IBLOCK_ID" => $ibID,
                "MULTIPLE" => "Y",
                "WITH_DESCRIPTION" => "Y"
            ]);
            Logger::log("Свойство GALLERY создано",
                Logger::COLOR_LIGHT_GREEN);

            $this->addIblockElementProperty([
                "NAME" => "Документы",
                "SORT" => 200,
                "CODE" => "DOCUMENTS",
                "PROPERTY_TYPE" => "F",
                "FILE_TYPE" => "doc, docx, xls, xlsx, txt, rtf, pdf",
                "IBLOCK_ID" => $ibID,
                "MULTIPLE" => "Y",
                "WITH_DESCRIPTION" => "Y"
            ]);
            Logger::log("Свойство DOCUMENTS создано",
                Logger::COLOR_LIGHT_GREEN);

            $this->addIblockElementProperty([
                "NAME" => "Видео",
                "SORT" => 300,
                "CODE" => "VIDEO",
                "IBLOCK_ID" => $ibID,
            ]);
            Logger::log("Свойство VIDEO создано",
                Logger::COLOR_LIGHT_GREEN);

            $this->addIblockElementProperty([
                "NAME" => "Избранный бренд",
                "SORT" => 400,
                "CODE" => "FAVORITE_BRAND",
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
            Logger::log("Свойство FAVORITE_BRAND создано",
                Logger::COLOR_LIGHT_GREEN);

            $this->addIblockElementProperty([
                "NAME" => "Преимущества",
                "SORT" => 500,
                "CODE" => "ADVANTAGES",
                "IBLOCK_ID" => $ibID,
                "USER_TYPE" => "HTML",
                "MULTIPLE" => "Y",
                "MULTIPLE_CNT" => 3,
                "WITH_DESCRIPTION" => "Y",
            ]);
            Logger::log("Свойство ADVANTAGES создано",
                Logger::COLOR_LIGHT_GREEN);

            $this->addIblockElementProperty([
                "NAME" => "Title",
                "SORT" => 600,
                "CODE" => "TITLE",
                "IBLOCK_ID" => $ibID,
            ]);
            Logger::log("Свойство TITLE создано",
                Logger::COLOR_LIGHT_GREEN);

            $this->addIblockElementProperty([
                "NAME" => "Description",
                "SORT" => 700,
                "CODE" => "DESCRIPTION",
                "IBLOCK_ID" => $ibID,
            ]);
            Logger::log("Свойство DESCRIPTION создано",
                Logger::COLOR_LIGHT_GREEN);

            $this->addIblockElementProperty([
                "NAME" => "Keywords",
                "SORT" => 800,
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
            $iblockId = $this->getIblockIdByCode("oip_brands");

            $this->deleteIblockElementPropertyByCode($iblockId, "KEYWORDS");
            Logger::log("Свойство KEYWORDS удалено",
                Logger::COLOR_LIGHT_GREEN);

            $this->deleteIblockElementPropertyByCode($iblockId, "DESCRIPTION");
            Logger::log("Свойство DESCRIPTION удалено",
                Logger::COLOR_LIGHT_GREEN);

            $this->deleteIblockElementPropertyByCode($iblockId, "TITLE");
            Logger::log("Свойство TITLE удалено",
                Logger::COLOR_LIGHT_GREEN);

            $this->deleteIblockElementPropertyByCode($iblockId, "ADVANTAGES");
            Logger::log("Свойство ADVANTAGES удалено",
                Logger::COLOR_LIGHT_GREEN);

            $this->deleteIblockElementPropertyByCode($iblockId, "FAVORITE_BRAND");
            Logger::log("Свойство FAVORITE_BRAND удалено",
                Logger::COLOR_LIGHT_GREEN);

            $this->deleteIblockElementPropertyByCode($iblockId, "VIDEO");
            Logger::log("Свойство VIDEO удалено",
                Logger::COLOR_LIGHT_GREEN);

            $this->deleteIblockElementPropertyByCode($iblockId, "DOCUMENTS");
            Logger::log("Свойство DOCUMENTS удалено",
                Logger::COLOR_LIGHT_GREEN);

            $this->deleteIblockElementPropertyByCode($iblockId, "GALLERY");
            Logger::log("Свойство GALLERY удалено",
                Logger::COLOR_LIGHT_GREEN);
        }
        catch(MigrationException $e) {
            Logger::log($e->getMessage(),
                Logger::COLOR_LIGHT_RED);
        }
    }
}
