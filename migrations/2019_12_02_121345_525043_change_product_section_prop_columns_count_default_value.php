<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Arrilot\BitrixMigrations\Logger;

class ChangeProductSectionPropColumnsCountDefaultValue20191202121345525043 extends BitrixMigration
{

    public function up()
    {
        try {
            $ibID = $this->getIblockIdByCode("oip_products");

            $ufId  = $this->getUFIdByCode("IBLOCK_{$ibID}_SECTION","UF_COLUMNS_COUNT");
            (new \CUserTypeEntity)->Delete($ufId);

            Logger::log("старое поле UF_COLUMNS_COUNT удалено",
                Logger::COLOR_LIGHT_GREEN);

            $this->addUF([
                "ENTITY_ID" => "IBLOCK_{$ibID}_SECTION",
                "FIELD_NAME" => "UF_COLUMNS_COUNT",
                "USER_TYPE_ID" => "string",
                "SORT" => 1600,
                "SETTINGS" => [
                    "SIZE" => 30,
                    "DEFAULT_VALUE" => "uk-child-width-1-1 uk-child-width-1-2@m uk-child-width-1-3@l uk-child-width-1-4@xl",
                ],
                "EDIT_FORM_LABEL" => [
                    "ru" => "Кол-во колонок",
                    "en" => "Columns count",
                ],
                "LIST_COLUMN_LABEL" => [
                    "ru" => "Кол-во колонок",
                    "en" => "Columns count",
                ],
            ]);
            Logger::log("новое поле UF_COLUMNS_COUNT создано",
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

            $ufId  = $this->getUFIdByCode("IBLOCK_{$ibID}_SECTION","UF_COLUMNS_COUNT");
            (new \CUserTypeEntity)->Delete($ufId);

            Logger::log("новое поле UF_COLUMNS_COUNT удалено",
                Logger::COLOR_LIGHT_GREEN);

            $this->addUF([
                "ENTITY_ID" => "IBLOCK_{$ibID}_SECTION",
                "FIELD_NAME" => "UF_COLUMNS_COUNT",
                "USER_TYPE_ID" => "integer",
                "SORT" => 1600,
                "SETTINGS" => [
                    "SIZE" => 3,
                    "DEFAULT_VALUE" => 3,
                ],
                "EDIT_FORM_LABEL" => [
                    "ru" => "Кол-во колонок",
                    "en" => "Columns count",
                ],
                "LIST_COLUMN_LABEL" => [
                    "ru" => "Кол-во колонок",
                    "en" => "Columns count",
                ],
            ]);
            Logger::log("новое поле UF_COLUMNS_COUNT создано",
                Logger::COLOR_LIGHT_GREEN);
        }
        catch(MigrationException $e) {
            Logger::log($e->getMessage(),
                Logger::COLOR_LIGHT_RED);
        }
    }
}
