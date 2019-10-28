<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Arrilot\BitrixMigrations\Logger;

class AddProductsIblock20191023092918181208 extends BitrixMigration
{
    public function up()
    {
        $arFields = [
            "NAME" => "Товары",
            "CODE" => "oip_products",
            "SORT" => 100,
            "SITE_ID" => "s1",
            "IBLOCK_TYPE_ID" => "oip_catalog",
            "GROUP_ID" => ["2" =>"R"],
            'LIST_PAGE_URL' => '/catalog/',
            'SECTION_PAGE_URL' => '/catalog/#SECTION_CODE_PATH#/',
            'DETAIL_PAGE_URL' => '/catalog/#SECTION_CODE_PATH#/#ELEMENT_CODE#/',
            "FIELDS" => [
                "CODE" => [
                    "IS_REQUIRED" => "Y",
                    "DEFAULT_VALUE" => [
                        "UNIQUE" => "Y",
                        "TRANSLITERATION" => "Y",
                        "TRANS_CASE" => "L",
                        "TRANS_SPACE" => "-",
                        "TRANS_OTHER" => "-",
                        "TRANS_EAT" => "Y"
                    ]
                ],
                "PREVIEW_PICTURE" => [
                    "DEFAULT_VALUE" => [
                        "FROM_DETAIL" => "Y",
                        "UPDATE_WITH_DETAIL" => "Y",
                        "DELETE_WITH_DETAIL" => "Y",
                        "SCALE" => "Y",
                        "WIDTH" => "600",
                        "METHOD" => "resample",
                        "COMPRESSION" => "90",
                    ]
                ],
                "DETAIL_PICTURE" => [
                    "DEFAULT_VALUE" => [
                        "SCALE" => "Y",
                        "WIDTH" => "1920",
                        "METHOD" => "resample",
                        "COMPRESSION" => "90",
                    ]
                ],
                'SECTION_CODE' => [
                    'IS_REQUIRED' => 'Y',
                    'DEFAULT_VALUE' => [
                        'UNIQUE' => 'Y',
                        'TRANSLITERATION' => 'Y',
                        'TRANS_CASE' => 'L',
                        'TRANS_SPACE' => '-',
                        'TRANS_OTHER' => '-',
                        'TRANS_EAT' => 'Y'
                    ]
                ],
                'SECTION_DETAIL_PICTURE' => [
                    'DEFAULT_VALUE' => [
                        'SCALE' => 'Y',
                        'WIDTH' => '1200',
                        'METHOD' => 'resample',
                        'COMPRESSION' => '90',
                    ]
                ],
                'SECTION_PICTURE' => [
                    'DEFAULT_VALUE' => [
                        'FROM_DETAIL' => 'Y',
                        'UPDATE_WITH_DETAIL' => 'Y',
                        'DELETE_WITH_DETAIL' => 'Y',
                        'SCALE' => 'Y',
                        'WIDTH' => '400',
                        'METHOD' => 'resample',
                        'COMPRESSION' => '90',
                   ]
               ],
            ]
        ];

        $ib = new \CIBlock;
        $ibID  = $ib->add($arFields);

        if(!$ibID) {
            Logger::log("Ошибка создания инфоблока ".$ib->LAST_ERROR,
                Logger::COLOR_LIGHT_RED);
        }
        else {
            Logger::log("Инфоблок oip_products создан",
                Logger::COLOR_LIGHT_GREEN);
        }
    }

    public function down()
    {
        try {
            $ibID = $this->getIblockIdByCode("oip_products");

            $this->db->startTransaction();

            if(!\CIBlock::Delete($ibID)) {
                $this->db->rollbackTransaction();
                Logger::log("Ошибка удаления инфоблока",
                    Logger::COLOR_LIGHT_RED);
            }
            else {
                $this->db->commitTransaction();
                Logger::log("Инфоблок oip_products удален",
                    Logger::COLOR_LIGHT_GREEN);
            }

        }
        catch (MigrationException $e) {
            Logger::log($e->getMessage(),
                Logger::COLOR_LIGHT_RED);
        }
        catch (\Bitrix\Main\DB\SqlQueryException $e) {
            Logger::log($e->getMessage(),
                Logger::COLOR_LIGHT_RED);
        }
    }
}
