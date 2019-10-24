<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Logger;

class AddOipCatalogIblockType20191021122416152752 extends BitrixMigration
{
    public function up()
    {
        $arFields = Array(
            'ID' => 'oip_catalog',
            'IN_RSS' => 'N',
            'SECTIONS' => 'Y',
            'SORT' =>10,
            'LANG' => [
                'en' => [
                    'NAME' => 'Catalog',
                    'SECTION_NAME' => 'Sections',
                    'ELEMENT_NAME' => 'Elements'
                ],
                'ru' => [
                    'NAME' => 'Каталог',
                    'SECTION_NAME' => 'Категории',
                    'ELEMENT_NAME' => 'Элементы'
                ],
            ]
        );

        try {
            $obBlocktype = new \CIBlockType;
            $this->db->startTransaction();
            $res = $obBlocktype->Add($arFields);
            if(!$res)
            {
                $this->db->rollbackTransaction();
                Logger::log("Ошибка создания типа инфоблока ".$obBlocktype->LAST_ERROR,
                    Logger::COLOR_LIGHT_RED);
            }
            else
                $this->db->commitTransaction();
            Logger::log("Тип инфоблока oip_catalog создан".$obBlocktype->LAST_ERROR,
                Logger::COLOR_LIGHT_GREEN);
        }
        catch(\Bitrix\Main\DB\SqlQueryException $e) {
            Logger::log($e->getMessage(),
                Logger::COLOR_LIGHT_RED);
        }

    }

    public function down()
    {
        try {
            $this->db->startTransaction();
            if (!\CIBlockType::Delete('oip_catalog')) {
                $this->db->rollbackTransaction();
                Logger::log("Ошибка удаления типа инфоблока",
                    Logger::COLOR_LIGHT_RED);
            }
            $this->db->commitTransaction();
            Logger::log("Типа инфоблока oip_catalog удален",
                Logger::COLOR_LIGHT_GREEN);
        }
        catch(\Bitrix\Main\DB\SqlQueryException $e) {
            Logger::log($e->getMessage(),
                Logger::COLOR_LIGHT_RED);
        }
    }
}
