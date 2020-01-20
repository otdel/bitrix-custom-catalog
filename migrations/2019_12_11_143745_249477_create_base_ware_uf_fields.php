<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Arrilot\BitrixMigrations\Logger;

class CreateBaseWareUfFields20191211143745249477 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        try {

            $ibID = $this->getIblockIdByCode("oip_products");

            $this->addIblockElementProperty([
                "NAME" => "Ware ID",
                "SORT" => 100,
                "CODE" => "WARE_ID",
                "IBLOCK_ID" => $ibID,
            ]);
            Logger::log("Свойство WARE_ID создано",
                Logger::COLOR_LIGHT_GREEN);

            $this->addIblockElementProperty([
                "NAME" => "Article",
                "SORT" => 100,
                "CODE" => "ARTICLE",
                "IBLOCK_ID" => $ibID,
            ]);
            Logger::log("Свойство ARTICLE создано",
                Logger::COLOR_LIGHT_GREEN);

            $this->addIblockElementProperty([
                "NAME" => "Color",
                "SORT" => 200,
                "CODE" => "COLOR",
                "IBLOCK_ID" => $ibID,
            ]);
            Logger::log("Свойство COLOR создано",
                Logger::COLOR_LIGHT_GREEN);

            $this->addIblockElementProperty([
                "NAME" => "Схема",
                "SORT" => 300,
                "CODE" => "SCHEME",
                "PROPERTY_TYPE" => "F",
                "FILE_TYPE" => "jpg, gif, bmp, png, jpeg",
                "IBLOCK_ID" => $ibID,
                "MULTIPLE" => "N",
                "WITH_DESCRIPTION" => "Y"
            ]);
            Logger::log("Свойство SCHEME создано",
                Logger::COLOR_LIGHT_GREEN);

            $this->addIblockElementProperty([
                "NAME" => "Guarantee",
                "SORT" => 400,
                "CODE" => "GUARANTEE",
                "IBLOCK_ID" => $ibID,
            ]);
            Logger::log("Свойство GUARANTEE создано",
                Logger::COLOR_LIGHT_GREEN);

        }
        catch(MigrationException $e) {
            Logger::log($e->getMessage(),
                Logger::COLOR_LIGHT_RED);
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
        try {
            $iblockId = $this->getIblockIdByCode("oip_products");

            $this->deleteIblockElementPropertyByCode($iblockId, "ARTICLE");
            Logger::log("Свойство ARTICLE удалено",
                Logger::COLOR_LIGHT_GREEN);

            $this->deleteIblockElementPropertyByCode($iblockId, "COLOR");
            Logger::log("Свойство COLOR удалено",
                Logger::COLOR_LIGHT_GREEN);

            $this->deleteIblockElementPropertyByCode($iblockId, "SCHEME");
            Logger::log("Свойство SCHEME удалено",
                Logger::COLOR_LIGHT_GREEN);

            $this->deleteIblockElementPropertyByCode($iblockId, "GUARANTEE");
            Logger::log("Свойство GUARANTEE удалено",
                Logger::COLOR_LIGHT_GREEN);

            $this->deleteIblockElementPropertyByCode($iblockId, "WARE_ID");
            Logger::log("Свойство WARE_ID удалено",
                Logger::COLOR_LIGHT_GREEN);

        }
        catch(MigrationException $e) {
            Logger::log($e->getMessage(),
                Logger::COLOR_LIGHT_RED);
        }
    }
}
