<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Arrilot\BitrixMigrations\Logger;

class AddProductsSectionProps20191023125849932210 extends BitrixMigration
{
    public function up()
    {
        try {

            $ibID = $this->getIblockIdByCode("oip_products");

            $ufId = $this->addUF([
                "ENTITY_ID" => "IBLOCK_{$ibID}_SECTION",
                "FIELD_NAME" => "UF_CATEGORY_TYPE",
                "USER_TYPE_ID" => "enumeration",
                "SORT" => 100,
                "MANDATORY" => "Y",
                "EDIT_FORM_LABEL" => [
                    "ru" => "Тип категории",
                    "en" => "Category type",
                ],
                "LIST_COLUMN_LABEL" => [
                    "ru" => "Тип категории",
                    "en" => "Category type",
                ],
            ]);

            $ufValues = [
                "n0" => [
                    'VALUE' => "товар",
                    'DEF' => 'Y',
                    'SORT' => 100
                ],
                "n1" => [
                    'VALUE' => "услуга",
                    'DEF' => 'N',
                    'SORT' => 200
                ],
            ];
            (new CUserFieldEnum())->SetEnumValues($ufId,$ufValues);

            Logger::log("поле UF_CATEGORY_TYPE создано",
                Logger::COLOR_LIGHT_GREEN);


            /*-----------------------------------------------------*/

            $this->addUF([
                "ENTITY_ID" => "IBLOCK_{$ibID}_SECTION",
                "FIELD_NAME" => "UF_ELEMENTS_NUMBER",
                "USER_TYPE_ID" => "integer",
                "SORT" => 200,
                "SETTINGS" => [
                    "DEFAULT_VALUE" => 24,
                    "SIZE" => 3,
                ],
                "EDIT_FORM_LABEL" => [
                    "ru" => "Количество элементов на странице",
                    "en" => "Elements number",
                ],
                "LIST_COLUMN_LABEL" => [
                    "ru" => "Количество элементов на странице",
                    "en" => "Elements number",
                ],
            ]);

            Logger::log("поле UF_ELEMENTS_NUMBER создано",
                Logger::COLOR_LIGHT_GREEN);

            /*-----------------------------------------------------*/

            $this->addUF([
                "ENTITY_ID" => "IBLOCK_{$ibID}_SECTION",
                "FIELD_NAME" => "UF_TITLE",
                "USER_TYPE_ID" => "string",
                "SORT" => 300,

                "EDIT_FORM_LABEL" => [
                    "ru" => "Title",
                    "en" => "Title",
                ],
                "LIST_COLUMN_LABEL" => [
                    "ru" => "Title",
                    "en" => "Title",
                ],
            ]);

            Logger::log("поле UF_TITLE создано",
                Logger::COLOR_LIGHT_GREEN);

            /*-----------------------------------------------------*/

            $this->addUF([
                "ENTITY_ID" => "IBLOCK_{$ibID}_SECTION",
                "FIELD_NAME" => "UF_DESCRIPTION",
                "USER_TYPE_ID" => "string",
                "SORT" => 400,

                "EDIT_FORM_LABEL" => [
                    "ru" => "Description",
                    "en" => "Description",
                ],
                "LIST_COLUMN_LABEL" => [
                    "ru" => "Description",
                    "en" => "Description",
                ],
            ]);

            Logger::log("поле UF_DESCRIPTION создано",
                Logger::COLOR_LIGHT_GREEN);

            /*-----------------------------------------------------*/

            $this->addUF([
                "ENTITY_ID" => "IBLOCK_{$ibID}_SECTION",
                "FIELD_NAME" => "UF_KEYWORDS",
                "USER_TYPE_ID" => "string",
                "SORT" => 600,

                "EDIT_FORM_LABEL" => [
                    "ru" => "Keywords",
                    "en" => "Keywords",
                ],
                "LIST_COLUMN_LABEL" => [
                    "ru" => "Keywords",
                    "en" => "Keywords",
                ],
            ]);

            Logger::log("поле UF_KEYWORDS создано",
                Logger::COLOR_LIGHT_GREEN);

            /*-----------------------------------------------------*/

            $this->addUF([
                "ENTITY_ID" => "IBLOCK_{$ibID}_SECTION",
                "FIELD_NAME" => "UF_CATEGORY_ICON",
                "USER_TYPE_ID" => "file",
                "SORT" => 700,

                "EDIT_FORM_LABEL" => [
                    "ru" => "Иконка категории",
                    "en" => "Category icon",
                ],
                "LIST_COLUMN_LABEL" => [
                    "ru" => "Иконка категории",
                    "en" => "Category icon",
                ],
            ]);

            Logger::log("поле UF_CATEGORY_ICON создано",
                Logger::COLOR_LIGHT_GREEN);

            /*-----------------------------------------------------*/

            $this->addUF([
                "ENTITY_ID" => "IBLOCK_{$ibID}_SECTION",
                "FIELD_NAME" => "UF_GALLERY",
                "USER_TYPE_ID" => "file",
                "MULTIPLE" => "Y",
                "SORT" => 800,
                "SETTINGS" => [
                    "EXTENSIONS" => "jpg, gif, bmp, png, jpeg"
                ],

                "EDIT_FORM_LABEL" => [
                    "ru" => "Галерея",
                    "en" => "Gallery",
                ],
                "LIST_COLUMN_LABEL" => [
                    "ru" => "Галерея",
                    "en" => "Gallery",
                ],
            ]);
            Logger::log("поле UF_GALLERY создано",
                Logger::COLOR_LIGHT_GREEN);

            /*-----------------------------------------------------*/

            $this->addUF([
                "ENTITY_ID" => "IBLOCK_{$ibID}_SECTION",
                "FIELD_NAME" => "UF_DOCUMENTS",
                "USER_TYPE_ID" => "file",
                "MULTIPLE" => "Y",
                "SORT" => 900,
                "SETTINGS" => [
                    "EXTENSIONS" => "doc, txt, rtf, docsx, xls, xlsx, pdf"
                ],

                "EDIT_FORM_LABEL" => [
                    "ru" => "Документы",
                    "en" => "Documents",
                ],
                "LIST_COLUMN_LABEL" => [
                    "ru" => "Документы",
                    "en" => "Documents",
                ],
            ]);
            Logger::log("поле UF_DOCUMENTS создано",
                Logger::COLOR_LIGHT_GREEN);

            /*-----------------------------------------------------*/

            $this->addUF([
                "ENTITY_ID" => "IBLOCK_{$ibID}_SECTION",
                "FIELD_NAME" => "UF_VIDEO",
                "USER_TYPE_ID" => "string",
                "SORT" => 1000,

                "EDIT_FORM_LABEL" => [
                    "ru" => "Видео",
                    "en" => "Video",
                ],
                "LIST_COLUMN_LABEL" => [
                    "ru" => "Видео",
                    "en" => "Video",
                ],
            ]);
            Logger::log("поле UF_VIDEO создано",
                Logger::COLOR_LIGHT_GREEN);

            /*-----------------------------------------------------*/

            $ufId = $this->addUF([
                "ENTITY_ID" => "IBLOCK_{$ibID}_SECTION",
                "FIELD_NAME" => "UF_SHOW_IN_TOP_MENU",
                "USER_TYPE_ID" => "enumeration",
                "SORT" => 1100,
                "EDIT_FORM_LABEL" => [
                    "ru" => "Показывать в верхнем меню",
                    "en" => "Show in top menu",
                ],
                "LIST_COLUMN_LABEL" => [
                    "ru" => "Показывать в верхнем меню",
                    "en" => "Show in top menu",
                ],
            ]);

            $ufValues = [
                "n0" => [
                    'VALUE' => "да",
                    'SORT' => 100
                ],
            ];
            (new CUserFieldEnum())->SetEnumValues($ufId,$ufValues);

            Logger::log("поле UF_SHOW_IN_TOP_MENU создано",
                Logger::COLOR_LIGHT_GREEN);

            /*-----------------------------------------------------*/

            $ufId = $this->addUF([
                "ENTITY_ID" => "IBLOCK_{$ibID}_SECTION",
                "FIELD_NAME" => "UF_SHOW_IN_MAIN_MENU",
                "USER_TYPE_ID" => "enumeration",
                "SORT" => 1200,
                "EDIT_FORM_LABEL" => [
                    "ru" => "Показывать в главном меню",
                    "en" => "Show in main menu",
                ],
                "LIST_COLUMN_LABEL" => [
                    "ru" => "Показывать в главном меню",
                    "en" => "Show in main menu",
                ],
            ]);

            $ufValues = [
                "n0" => [
                    'VALUE' => "да",
                    'SORT' => 100
                ],
            ];
            (new CUserFieldEnum())->SetEnumValues($ufId,$ufValues);

            Logger::log("поле UF_SHOW_IN_MAIN_MENU создано",
                Logger::COLOR_LIGHT_GREEN);

            /*-----------------------------------------------------*/

            $ufId = $this->addUF([
                "ENTITY_ID" => "IBLOCK_{$ibID}_SECTION",
                "FIELD_NAME" => "UF_SHOW_IN_INDEX",
                "USER_TYPE_ID" => "enumeration",
                "SORT" => 1300,
                "EDIT_FORM_LABEL" => [
                    "ru" => "Показывать на главной",
                    "en" => "Show in index page",
                ],
                "LIST_COLUMN_LABEL" => [
                    "ru" => "Показывать на главной",
                    "en" => "Show in index page",
                ],
            ]);

            $ufValues = [
                "n0" => [
                    'VALUE' => "да",
                    'SORT' => 100
                ],
            ];
            (new CUserFieldEnum())->SetEnumValues($ufId,$ufValues);

            Logger::log("поле UF_SHOW_IN_INDEX создано",
                Logger::COLOR_LIGHT_GREEN);

            /*-----------------------------------------------------*/

            $this->addUF([
                "ENTITY_ID" => "IBLOCK_{$ibID}_SECTION",
                "FIELD_NAME" => "UF_VIEWS_COUNT",
                "USER_TYPE_ID" => "integer",
                "SORT" => 1400,
                "SETTINGS" => [
                    "SIZE" => 3,
                ],
                "EDIT_FORM_LABEL" => [
                    "ru" => "Кол-во просмотров",
                    "en" => "Views count",
                ],
                "LIST_COLUMN_LABEL" => [
                    "ru" => "Кол-во просмотров",
                    "en" => "Views count",
                ],
            ]);
            Logger::log("поле UF_VIEWS_COUNT создано",
                Logger::COLOR_LIGHT_GREEN);

            /*-----------------------------------------------------*/

            $this->addUF([
                "ENTITY_ID" => "IBLOCK_{$ibID}_SECTION",
                "FIELD_NAME" => "UF_LIST_TITLE",
                "USER_TYPE_ID" => "string",
                "SORT" => 1500,

                "EDIT_FORM_LABEL" => [
                    "ru" => "Заголовок списка",
                    "en" => "List title",
                ],
                "LIST_COLUMN_LABEL" => [
                    "ru" => "Заголовок списка",
                    "en" => "List title",
                ],
            ]);
            Logger::log("поле UF_LIST_TITLE создано",
                Logger::COLOR_LIGHT_GREEN);

            /*-----------------------------------------------------*/

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
            Logger::log("поле UF_COLUMNS_COUNT создано",
                Logger::COLOR_LIGHT_GREEN);

            /*-----------------------------------------------------*/

            $this->addUF([
                "ENTITY_ID" => "IBLOCK_{$ibID}_SECTION",
                "FIELD_NAME" => "UF_SIDEBAR_WIDTH",
                "USER_TYPE_ID" => "integer",
                "SORT" => 1700,
                "SETTINGS" => [
                    "SIZE" => 3,
                    "DEFAULT_VALUE" => 5,
                ],
                "EDIT_FORM_LABEL" => [
                    "ru" => "Ширина сайдбара",
                    "en" => "Sidebar width",
                ],
                "LIST_COLUMN_LABEL" => [
                    "ru" => "Ширина сайдбара",
                    "en" => "Sidebar width",
                ],
            ]);
            Logger::log("поле UF_SIDEBAR_WIDTH создано",
                Logger::COLOR_LIGHT_GREEN);

            /*-----------------------------------------------------*/

            $this->addUF([
                "ENTITY_ID" => "IBLOCK_{$ibID}_SECTION",
                "FIELD_NAME" => "UF_ELEMENT_TITLE_CSS",
                "USER_TYPE_ID" => "string",
                "SORT" => 1800,
                "SETTINGS" => [
                    "DEFAULT_VALUE" => "uk-h5",
                ],
                "EDIT_FORM_LABEL" => [
                    "ru" => "Класс заголовка элемента",
                    "en" => "Element title css",
                ],
                "LIST_COLUMN_LABEL" => [
                    "ru" => "Класс заголовка элемента",
                    "en" => "Element title css",
                ],
            ]);
            Logger::log("поле UF_ELEMENT_TITLE_CSS создано",
                Logger::COLOR_LIGHT_GREEN);

            /*-----------------------------------------------------*/

            $ufId = $this->addUF([
                "ENTITY_ID" => "IBLOCK_{$ibID}_SECTION",
                "FIELD_NAME" => "UF_SIDEBAR_LIST",
                "USER_TYPE_ID" => "enumeration",
                "SORT" => 1900,
                "EDIT_FORM_LABEL" => [
                    "ru" => "Показывать сайдбар на странице списка",
                    "en" => "Show sidebar in list page",
                ],
                "LIST_COLUMN_LABEL" => [
                    "ru" => "Показывать сайдбар на странице списка",
                    "en" => "Show sidebar in list page",
                ],
            ]);

            $ufValues = [
                "n0" => [
                    'VALUE' => "да",
                    'SORT' => 100
                ],
            ];
            (new CUserFieldEnum())->SetEnumValues($ufId,$ufValues);

            Logger::log("поле UF_SIDEBAR_LIST создано",
                Logger::COLOR_LIGHT_GREEN);

            /*-----------------------------------------------------*/

            $ufId = $this->addUF([
                "ENTITY_ID" => "IBLOCK_{$ibID}_SECTION",
                "FIELD_NAME" => "UF_SIDEBAR_ELEMENT",
                "USER_TYPE_ID" => "enumeration",
                "SORT" => 2000,
                "EDIT_FORM_LABEL" => [
                    "ru" => "Показывать сайдбар на странице элемента",
                    "en" => "Show sidebar in element page",
                ],
                "LIST_COLUMN_LABEL" => [
                    "ru" => "Показывать сайдбар на странице элемента",
                    "en" => "Show sidebar in element page",
                ],
            ]);

            $ufValues = [
                "n0" => [
                    'VALUE' => "да",
                    'SORT' => 100
                ],
            ];
            (new CUserFieldEnum())->SetEnumValues($ufId,$ufValues);

            Logger::log("поле UF_SIDEBAR_ELEMENT создано",
                Logger::COLOR_LIGHT_GREEN);

            /*-----------------------------------------------------*/

            $ufId = $this->addUF([
                "ENTITY_ID" => "IBLOCK_{$ibID}_SECTION",
                "FIELD_NAME" => "UF_SAME_ELEMENT",
                "USER_TYPE_ID" => "enumeration",
                "SORT" => 2100,
                "EDIT_FORM_LABEL" => [
                    "ru" => "Показывать похожие товары на странице элемента",
                    "en" => "Show same products in element page",
                ],
                "LIST_COLUMN_LABEL" => [
                    "ru" => "Показывать похожие товары на странице элемента",
                    "en" => "Show same products in element page",
                ],
            ]);

            $ufValues = [
                "n0" => [
                    'VALUE' => "да",
                    'DEF' => 'Y',
                    'SORT' => 100
                ],
            ];
            (new CUserFieldEnum())->SetEnumValues($ufId,$ufValues);

            Logger::log("поле UF_SAME_ELEMENT создано",
                Logger::COLOR_LIGHT_GREEN);

            /*-----------------------------------------------------*/

            $ufId = $this->addUF([
                "ENTITY_ID" => "IBLOCK_{$ibID}_SECTION",
                "FIELD_NAME" => "UF_POPULAR_WITH_THIS",
                "USER_TYPE_ID" => "enumeration",
                "SORT" => 2200,
                "EDIT_FORM_LABEL" => [
                    "ru" => "Показывать блок C этим товаров часто смотрят",
                    "en" => "Show Popular with this block",
                ],
                "LIST_COLUMN_LABEL" => [
                    "ru" => "Показывать блок C этим товаров часто смотрят",
                    "en" => "Show Popular with this block",
                ],
            ]);

            $ufValues = [
                "n0" => [
                    'VALUE' => "да",
                    'DEF' => 'Y',
                    'SORT' => 100
                ],
            ];
            (new CUserFieldEnum())->SetEnumValues($ufId,$ufValues);

            Logger::log("поле UF_POPULAR_WITH_THIS создано",
                Logger::COLOR_LIGHT_GREEN);


            /*-----------------------------------------------------*/

            $linkIblockID = $this->getIblockIdByCode("oip_special_offers");
            $this->addUF([
                "ENTITY_ID" => "IBLOCK_{$ibID}_SECTION",
                "FIELD_NAME" => "UF_SPECIAL_OFFERS",
                "USER_TYPE_ID" => "iblock_element",
                "SORT" => 2300,
                "MULTIPLE" => "Y",
                "SETTINGS" => [
                    "IBLOCK_ID" => $linkIblockID,
                    "LIST_HEIGHT" => 5
                ],
                "EDIT_FORM_LABEL" => [
                    "ru" => "Спецпредложения",
                    "en" => "Special offers",
                ],
                "LIST_COLUMN_LABEL" => [
                    "ru" => "Спецпредложения",
                    "en" => "Special offers",
                ],
            ]);

            Logger::log("поле UF_SPECIAL_OFFERS создано",
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


            /*--------------------------------------------------------------------------------------*/
            $ufId  = $this->getUFIdByCode("IBLOCK_{$ibID}_SECTION","UF_SPECIAL_OFFERS");
            (new \CUserTypeEntity)->Delete($ufId);

            Logger::log("поле UF_SPECIAL_OFFERS удалено",
                Logger::COLOR_LIGHT_GREEN);

            /*--------------------------------------------------------------------------------------*/
            $ufId  = $this->getUFIdByCode("IBLOCK_{$ibID}_SECTION","UF_POPULAR_WITH_THIS");
            (new \CUserTypeEntity)->Delete($ufId);

            Logger::log("поле UF_POPULAR_WITH_THIS удалено",
                Logger::COLOR_LIGHT_GREEN);

            /*--------------------------------------------------------------------------------------*/
            $ufId  = $this->getUFIdByCode("IBLOCK_{$ibID}_SECTION","UF_SAME_ELEMENT");
            (new \CUserTypeEntity)->Delete($ufId);

            Logger::log("поле UF_SAME_ELEMENT удалено",
                Logger::COLOR_LIGHT_GREEN);

            /*--------------------------------------------------------------------------------------*/
            $ufId  = $this->getUFIdByCode("IBLOCK_{$ibID}_SECTION","UF_SIDEBAR_ELEMENT");
            (new \CUserTypeEntity)->Delete($ufId);

            Logger::log("поле UF_SIDEBAR_ELEMENT удалено",
                Logger::COLOR_LIGHT_GREEN);

            /*--------------------------------------------------------------------------------------*/
            $ufId  = $this->getUFIdByCode("IBLOCK_{$ibID}_SECTION","UF_SIDEBAR_LIST");
            (new \CUserTypeEntity)->Delete($ufId);

            Logger::log("поле UF_SIDEBAR_LIST удалено",
                Logger::COLOR_LIGHT_GREEN);

            /*--------------------------------------------------------------------------------------*/
            $ufId  = $this->getUFIdByCode("IBLOCK_{$ibID}_SECTION","UF_ELEMENT_TITLE_CSS");
            (new \CUserTypeEntity)->Delete($ufId);

            Logger::log("поле UF_ELEMENT_TITLE_CSS удалено",
                Logger::COLOR_LIGHT_GREEN);

            /*--------------------------------------------------------------------------------------*/
            $ufId  = $this->getUFIdByCode("IBLOCK_{$ibID}_SECTION","UF_SIDEBAR_WIDTH");
            (new \CUserTypeEntity)->Delete($ufId);

            Logger::log("поле UF_SIDEBAR_WIDTH удалено",
                Logger::COLOR_LIGHT_GREEN);

            /*--------------------------------------------------------------------------------------*/
            $ufId  = $this->getUFIdByCode("IBLOCK_{$ibID}_SECTION","UF_COLUMNS_COUNT");
            (new \CUserTypeEntity)->Delete($ufId);

            Logger::log("поле UF_COLUMNS_COUNT удалено",
                Logger::COLOR_LIGHT_GREEN);

            /*--------------------------------------------------------------------------------------*/
            $ufId  = $this->getUFIdByCode("IBLOCK_{$ibID}_SECTION","UF_LIST_TITLE");
            (new \CUserTypeEntity)->Delete($ufId);

            Logger::log("поле UF_LIST_TITLE удалено",
                Logger::COLOR_LIGHT_GREEN);

            /*--------------------------------------------------------------------------------------*/
            $ufId  = $this->getUFIdByCode("IBLOCK_{$ibID}_SECTION","UF_VIEWS_COUNT");
            (new \CUserTypeEntity)->Delete($ufId);

            Logger::log("поле UF_VIEWS_COUNT удалено",
                Logger::COLOR_LIGHT_GREEN);

            /*--------------------------------------------------------------------------------------*/
            $ufId  = $this->getUFIdByCode("IBLOCK_{$ibID}_SECTION","UF_SHOW_IN_INDEX");
            (new \CUserTypeEntity)->Delete($ufId);

            Logger::log("поле UF_SHOW_IN_INDEX удалено",
                Logger::COLOR_LIGHT_GREEN);

            /*--------------------------------------------------------------------------------------*/
            $ufId  = $this->getUFIdByCode("IBLOCK_{$ibID}_SECTION","UF_SHOW_IN_MAIN_MENU");
            (new \CUserTypeEntity)->Delete($ufId);

            Logger::log("поле UF_SHOW_IN_MAIN_MENU удалено",
                Logger::COLOR_LIGHT_GREEN);

            /*--------------------------------------------------------------------------------------*/
            $ufId  = $this->getUFIdByCode("IBLOCK_{$ibID}_SECTION","UF_SHOW_IN_TOP_MENU");
            (new \CUserTypeEntity)->Delete($ufId);

            Logger::log("поле UF_SHOW_IN_TOP_MENU удалено",
                Logger::COLOR_LIGHT_GREEN);

            /*--------------------------------------------------------------------------------------*/
            $ufId  = $this->getUFIdByCode("IBLOCK_{$ibID}_SECTION","UF_VIDEO");
            (new \CUserTypeEntity)->Delete($ufId);

            Logger::log("поле UF_VIDEO удалено",
                Logger::COLOR_LIGHT_GREEN);

            /*--------------------------------------------------------------------------------------*/
            $ufId  = $this->getUFIdByCode("IBLOCK_{$ibID}_SECTION","UF_DOCUMENTS");
            (new \CUserTypeEntity)->Delete($ufId);

            Logger::log("поле UF_DOCUMENTS удалено",
                Logger::COLOR_LIGHT_GREEN);
            /*--------------------------------------------------------------------------------------*/
            $ufId  = $this->getUFIdByCode("IBLOCK_{$ibID}_SECTION","UF_GALLERY");
            (new \CUserTypeEntity)->Delete($ufId);

            Logger::log("поле UF_GALLERY удалено",
                Logger::COLOR_LIGHT_GREEN);
            /*--------------------------------------------------------------------------------------*/
            $ufId  = $this->getUFIdByCode("IBLOCK_{$ibID}_SECTION","UF_CATEGORY_ICON");
            (new \CUserTypeEntity)->Delete($ufId);

            Logger::log("поле UF_CATEGORY_ICON удалено",
                Logger::COLOR_LIGHT_GREEN);


            /*--------------------------------------------------------------------------------------*/

            $ufId  = $this->getUFIdByCode("IBLOCK_{$ibID}_SECTION","UF_KEYWORDS");
            (new \CUserTypeEntity)->Delete($ufId);

            Logger::log("поле UF_KEYWORDS удалено",
                Logger::COLOR_LIGHT_GREEN);

            /*--------------------------------------------------------------------------------------*/

            $ufId  = $this->getUFIdByCode("IBLOCK_{$ibID}_SECTION","UF_DESCRIPTION");
            (new \CUserTypeEntity)->Delete($ufId);

            Logger::log("поле UF_DESCRIPTION удалено",
                Logger::COLOR_LIGHT_GREEN);

            /*--------------------------------------------------------------------------------------*/

            $ufId  = $this->getUFIdByCode("IBLOCK_{$ibID}_SECTION","UF_TITLE");
            (new \CUserTypeEntity)->Delete($ufId);

            Logger::log("поле UF_TITLE удалено",
                Logger::COLOR_LIGHT_GREEN);

            /*--------------------------------------------------------------------------------------*/

            $ufId  = $this->getUFIdByCode("IBLOCK_{$ibID}_SECTION","UF_ELEMENTS_NUMBER");
            (new \CUserTypeEntity)->Delete($ufId);

            Logger::log("поле UF_ELEMENTS_NUMBER удалено",
                Logger::COLOR_LIGHT_GREEN);

            /*--------------------------------------------------------------------------------------*/


            $ufId  = $this->getUFIdByCode("IBLOCK_{$ibID}_SECTION","UF_CATEGORY_TYPE");
            (new \CUserTypeEntity)->Delete($ufId);

            Logger::log("поле UF_CATEGORY_TYPE удалено",
                Logger::COLOR_LIGHT_GREEN);

        }
        catch(MigrationException $e) {
            Logger::log($e->getMessage(),
                Logger::COLOR_LIGHT_RED);
        }
    }
}
