<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Arrilot\BitrixMigrations\Logger;

class AddProductsIblockProps20191023110911037006 extends BitrixMigration
{
    public function up()
    {
        try {

            $ibID = $this->getIblockIdByCode("oip_products");

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
                "NAME" => "Галерея",
                "SORT" => 400,
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
                "NAME" => "Видео",
                "SORT" => 500,
                "CODE" => "VIDEO",
                "IBLOCK_ID" => $ibID,
            ]);
            Logger::log("Свойство VIDEO создано",
                Logger::COLOR_LIGHT_GREEN);

            $this->addIblockElementProperty([
                "NAME" => "Показывать на главной",
                "SORT" => 700,
                "CODE" => "SHOW_ON_INDEX",
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
            Logger::log("Свойство SHOW_ON_INDEX создано",
                Logger::COLOR_LIGHT_GREEN);

            $this->addIblockElementProperty([
                "NAME" => "Показывать слайдер вместо превью-картинки в списке",
                "SORT" => 800,
                "CODE" => "SHOW_SLIDER_INSTEAD_PREVIEW",
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
            Logger::log("Свойство SHOW_SLIDER_INSTEAD_PREVIEW создано",
                Logger::COLOR_LIGHT_GREEN);

            $this->addIblockElementProperty([
                "NAME" => "Кол-во просмотров",
                "SORT" => 900,
                "CODE" => "VIEWS_COUNT",
                "PROPERTY_TYPE" => "N",
                "COL_COUNT" => 3,
                "IBLOCK_ID" => $ibID,
            ]);
            Logger::log("Свойство VIEWS_COUNT создано",
                Logger::COLOR_LIGHT_GREEN);

            $this->addIblockElementProperty([
                "NAME" => "Кол-во лайков",
                "SORT" => 900,
                "CODE" => "LIKES_COUNT",
                "PROPERTY_TYPE" => "N",
                "COL_COUNT" => 3,
                "IBLOCK_ID" => $ibID,
            ]);
            Logger::log("Свойство LIKES_COUNT создано",
                Logger::COLOR_LIGHT_GREEN);

            $this->addIblockElementProperty([
                "NAME" => "Бейджик",
                "SORT" => 1000,
                "CODE" => "BADGE",
                "IBLOCK_ID" => $ibID,
            ]);
            Logger::log("Свойство BADGE создано",
                Logger::COLOR_LIGHT_GREEN);

            $this->addIblockElementProperty([
                "NAME" => "Бренды",
                "SORT" => 1100,
                "CODE" => "BRANDS",
                "PROPERTY_TYPE" => "E",
                "IBLOCK_ID" => $ibID,
                "LINK_IBLOCK_ID" => $this->getIblockIdByCode("oip_brands"),
                "MULTIPLE" => "Y",
                "IS_REQUIRED" => "Y",
            ]);
            Logger::log("Свойство BRANDS создано",
                Logger::COLOR_LIGHT_GREEN);

            $this->addIblockElementProperty([
                "NAME" => "Теги",
                "SORT" => 1200,
                "CODE" => "TAGS",
                "PROPERTY_TYPE" => "E",
                "IBLOCK_ID" => $ibID,
                "LINK_IBLOCK_ID" => $this->getIblockIdByCode("oip_tags"),
                "MULTIPLE" => "Y",
            ]);
            Logger::log("Свойство TAGS создано",
                Logger::COLOR_LIGHT_GREEN);

            $this->addIblockElementProperty([
                "NAME" => "Отзывы",
                "SORT" => 1300,
                "CODE" => "REVIEWS",
                "PROPERTY_TYPE" => "E",
                "IBLOCK_ID" => $ibID,
                "LINK_IBLOCK_ID" => $this->getIblockIdByCode("oip_reviews"),
                "MULTIPLE" => "Y",
            ]);
            Logger::log("Свойство REVIEWS создано",
                Logger::COLOR_LIGHT_GREEN);

            $this->addIblockElementProperty([
                "NAME" => "Спецпредложения",
                "SORT" => 1400,
                "CODE" => "OFFERS",
                "PROPERTY_TYPE" => "E",
                "IBLOCK_ID" => $ibID,
                "LINK_IBLOCK_ID" => $this->getIblockIdByCode("oip_special_offers"),
                "MULTIPLE" => "Y",
            ]);
            Logger::log("Свойство OFFERS создано",
                Logger::COLOR_LIGHT_GREEN);

            $this->addIblockElementProperty([
                "NAME" => "Отображение детальной картинки",
                "SORT" => 1500,
                "CODE" => "PICTURE_VIEW_TYPE",
                "IBLOCK_ID" => $ibID,
                "PROPERTY_TYPE" => "L",
                "IS_REQUIRED" => "Y",
                "VALUES" => [
                    0 => [
                        "VALUE" => "помещать",
                        "SORT" => 100,
                        "DEF" => "Y"
                    ],
                    1 => [
                        "VALUE" => "обрезать",
                        "SORT" => 200,
                    ],
                ]
            ]);
            Logger::log("Свойство PICTURE_VIEW_TYPE создано",
                Logger::COLOR_LIGHT_GREEN);

            $this->addIblockElementProperty([
                "NAME" => "Характеристики",
                "SORT" => 1600,
                "CODE" => "CHARACTERISTICS",
                "IBLOCK_ID" => $ibID,
                "USER_TYPE" => "HTML",
                "MULTIPLE" => "Y",
                "MULTIPLE_CNT" => 3,
                "WITH_DESCRIPTION" => "Y",
            ]);
            Logger::log("Свойство CHARACTERISTICS создано",
                Logger::COLOR_LIGHT_GREEN);

            $this->addIblockElementProperty([
                "NAME" => "Преимущества",
                "SORT" => 1700,
                "CODE" => "ADVANTAGES",
                "IBLOCK_ID" => $ibID,
                "USER_TYPE" => "HTML",
                "MULTIPLE" => "Y",
                "MULTIPLE_CNT" => 3,
                "WITH_DESCRIPTION" => "Y",
            ]);
            Logger::log("Свойство ADVANTAGES создано",
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
            $iblockId = $this->getIblockIdByCode("oip_products");

            $this->deleteIblockElementPropertyByCode($iblockId, "ADVANTAGES");
            Logger::log("Свойство ADVANTAGES удалено",
                Logger::COLOR_LIGHT_GREEN);

            $this->deleteIblockElementPropertyByCode($iblockId, "CHARACTERISTICS");
            Logger::log("Свойство CHARACTERISTICS удалено",
                Logger::COLOR_LIGHT_GREEN);

            $this->deleteIblockElementPropertyByCode($iblockId, "PICTURE_VIEW_TYPE");
            Logger::log("Свойство PICTURE_VIEW_TYPE удалено",
                Logger::COLOR_LIGHT_GREEN);

            $this->deleteIblockElementPropertyByCode($iblockId, "OFFERS");
            Logger::log("Свойство OFFERS удалено",
                Logger::COLOR_LIGHT_GREEN);

            $this->deleteIblockElementPropertyByCode($iblockId, "REVIEWS");
            Logger::log("Свойство REVIEWS удалено",
                Logger::COLOR_LIGHT_GREEN);

            $this->deleteIblockElementPropertyByCode($iblockId, "TAGS");
            Logger::log("Свойство TAGS удалено",
                Logger::COLOR_LIGHT_GREEN);

            $this->deleteIblockElementPropertyByCode($iblockId, "BRANDS");
            Logger::log("Свойство BRANDS удалено",
                Logger::COLOR_LIGHT_GREEN);

            $this->deleteIblockElementPropertyByCode($iblockId, "BADGE");
            Logger::log("Свойство BADGE удалено",
                Logger::COLOR_LIGHT_GREEN);

            $this->deleteIblockElementPropertyByCode($iblockId, "LIKES_COUNT");
            Logger::log("Свойство LIKES_COUNT удалено",
                Logger::COLOR_LIGHT_GREEN);

            $this->deleteIblockElementPropertyByCode($iblockId, "VIEWS_COUNT");
            Logger::log("Свойство VIEWS_COUNT удалено",
                Logger::COLOR_LIGHT_GREEN);

            $this->deleteIblockElementPropertyByCode($iblockId, "SHOW_SLIDER_INSTEAD_PREVIEW");
            Logger::log("Свойство SHOW_SLIDER_INSTEAD_PREVIEW удалено",
                Logger::COLOR_LIGHT_GREEN);

            $this->deleteIblockElementPropertyByCode($iblockId, "SHOW_ON_INDEX");
            Logger::log("Свойство SHOW_ON_INDEX удалено",
                Logger::COLOR_LIGHT_GREEN);

            $this->deleteIblockElementPropertyByCode($iblockId, "VIDEO");
            Logger::log("Свойство VIDEO удалено",
                Logger::COLOR_LIGHT_GREEN);

            $this->deleteIblockElementPropertyByCode($iblockId, "GALLERY");
            Logger::log("Свойство GALLERY удалено",
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
