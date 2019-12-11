<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\ArgumentTypeException;

\CBitrixComponent::includeComponentClass("oip:component");

abstract class COipIblockElement extends \COipComponent
{
    /**
     * @return array
     * @param  $arParams
     * @throws ArgumentNullException | ArgumentTypeException
     */
    protected function initParams($arParams) {

        $arParams = parent::initParams($arParams);

        if(!is_set($arParams["IBLOCK_ID"])) {
            throw new ArgumentNullException("IBLOCK_ID");
        }

        if(!intval($arParams["IBLOCK_ID"])) {
            throw new ArgumentTypeException("IBLOCK_ID");
        }

        $this->setDefaultParam($arParams["SECTION_ID"],0);
        $this->setDefaultParam($arParams["SECTION_CODE"],"");
        $this->setDefaultParam($arParams["FILTER_ID"],0);
        $this->setDefaultParam($arParams["FILTER_PARAMS"], []);
        $this->setDefaultParam($arParams["SECTION_NAME"],"");
        $this->setDefaultParam($arParams["RESIZE_FILE_PROPS"],["width" => 600, "height" => 600]);
        $this->setDefaultBooleanParam($arParams["SHOW_INACTIVE"]);
        $this->setDefaultParam( $arParams["PROPERTIES"],[]);

        $this->setDefaultBooleanParam( $arParams["SHOW_META"]);
        $this->setDefaultBooleanParam( $arParams["INCLUDE_IBLOCK_CHAIN"]);

        $this->setDefaultBooleanParam( $arParams["CHECK_PERMISSIONS"],true);

        $this->setDefaultBooleanParam( $arParams["SHOW_404"],true);

        $this->setDefaultParam( $arParams["COUNT"],24,$arParams["_COUNT"]);

        $this->setDefaultParam( $arParams["FILTER"],"");
        $this->setDefaultParam( $arParams["SORT_1"],"sort");
        $this->setDefaultParam( $arParams["BY_1"],"asc");
        $this->setDefaultParam( $arParams["SORT_2"],"active_from");
        $this->setDefaultParam( $arParams["BY_2"],"desc");

        $this->setDefaultParam($arParams["LIST_VIEW_TITLE_TEXT"],"");
        $this->setDefaultParam($arParams["LIST_VIEW_TITLE_TAG"],"div");
        $this->setDefaultParam($arParams["LIST_VIEW_TITLE_CSS"],"uk-h1",
            $arParams["_LIST_VIEW_TITLE_CSS"]);
        $this->setDefaultParam($arParams["LIST_VIEW_TITLE_ICON_CSS"],"");
        $this->setDefaultParam($arParams["LIST_VIEW_TITLE_ALIGN"],"left");

        $this->setDefaultParam($arParams["LIST_VIEW_WRAP_COLOR"],"default");
        $this->setDefaultParam($arParams["LIST_VIEW_WRAP_SIZE"],"small");
        $this->setDefaultParam($arParams["LIST_VIEW_WRAP_ADD_CSS"],"");

        $this->setDefaultParam($arParams["LIST_VIEW_CONTAINER_WIDTH_CSS"],"expand");
        $this->setDefaultParam($arParams["LIST_VIEW_CONTAINER_TYPE"],"TILE");
        $this->setDefaultParam($arParams["LIST_VIEW_CONTAINER_ELEMENT_WIDTH_CSS"],
            "uk-child-width-1-1 uk-child-width-1-2@m uk-child-width-1-3@l uk-child-width-1-4@xl",
            $arParams["_LIST_VIEW_CONTAINER_ELEMENT_WIDTH_CSS"]);
        $this->setDefaultParam($arParams["LIST_VIEW_CONTAINER_MARGIN_CSS"],"medium");
        $this->setDefaultBooleanParam($arParams["LIST_VIEW_CONTAINER_VERTICAL_ALIGN"],true);

        $this->setDefaultParam($arParams["TILE_DYNAMIC"], "false");
        $this->setDefaultParam($arParams["TILE_PARALLAX"],0);
        $this->setDefaultParam($arParams["TILE_VERTICAL_ALIGN"],"left@m");
        $this->setDefaultParam($arParams["TILE_HORIZONTAL_MARGIN"],
            "medium");
        $this->setDefaultParam($arParams["TILE_VERTICAL_MARGIN"],
            "medium");

        $this->setDefaultBooleanParam($arParams["SLIDER_SHOW_ARROWS"],true);
        $this->setDefaultBooleanParam($arParams["SLIDER_SHOW_BULLETS"]);
        $this->setDefaultParam($arParams["SLIDER_AUTOPLAY"],"");
        $this->setDefaultParam($arParams["SLIDER_AUTOPLAY_INTERVAL"],6000);
        $this->setDefaultParam($arParams["SLIDER_CENTERED"],"");
        $this->setDefaultParam($arParams["SLIDER_MOVE_SETS"],"");
        $this->setDefaultBooleanParam($arParams["SLIDER_CONTENT_ON_PICTURE"]);

        $this->setDefaultParam($arParams["ELEMENT_VIEW_PICTURE_TYPE"],
            "contain");
        $this->setDefaultParam($arParams["ELEMENT_VIEW_PICTURE_HEIGHT"],
            "small");
        $this->setDefaultParam($arParams["ELEMENT_VIEW_PICTURE_POSITION"],
            "top");

        $this->setDefaultParam($arParams["ELEMENT_VIEW_BLOCK_COLOR"],
            "default");
        $this->setDefaultParam($arParams["ELEMENT_VIEW_BLOCK_SIZE"],
            "medium");

        $this->setDefaultParam($arParams["ELEMENT_VIEW_TITLE_ALIGN"],
            "center");
        $this->setDefaultParam($arParams["ELEMENT_VIEW_TITLE_CSS"],
            "");
        $this->setDefaultBooleanParam($arParams["ELEMENT_VIEW_SHOW_HOVER_EFFECT"],true);
        $this->setDefaultParam($arParams["ELEMENT_VIEW_HOVER_EFFECT_CSS"],"scale-down");

        $this->setDefaultBooleanParam($arParams["ELEMENT_VIEW_SHOW_CATEGORY_NAME"],true);
        $this->setDefaultBooleanParam($arParams["ELEMENT_VIEW_SHOW_TAG_LIST"]);
        $this->setDefaultBooleanParam($arParams["ELEMENT_VIEW_SHOW_BRAND"],true);
        $this->setDefaultBooleanParam($arParams["ELEMENT_VIEW_SHOW_REVIEWS_NUMBER"],true);

        $this->setDefaultBooleanParam($arParams["ELEMENT_VIEW_SHOW_READ_MORE_BUTTON"]);
        $this->setDefaultParam($arParams["ELEMENT_VIEW_READ_MORE_BUTTON_TEXT"],
            "подробнее");

        if(is_array($arParams["PROPERTIES"])) {
            $arParams["PROPERTIES"] = $this->trimPropCodes($arParams["PROPERTIES"]);
        }

        return $arParams;
    }

    /**
     * @param array $propCodes
     * @return array
     */
    protected function trimPropCodes($propCodes) {
        return array_map(function ($propCode) {
            return trim($propCode);
        }, $propCodes);
    }
}