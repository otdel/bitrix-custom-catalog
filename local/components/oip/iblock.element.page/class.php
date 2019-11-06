<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

\CBitrixComponent::includeComponentClass("oip:iblock.element");

class COipIblockElementPage extends \COipIblockElement {
    public function executeComponent() {
        $this->includeComponentTemplate();
    }

    /**
     * @param  $arParams
     * @return array
     */
    protected function initParams($arParams) {

        $arParams = parent::initParams($arParams);

        $this->setDefaultBooleanParam( $arParams["SHOW_SIDEBAR"]);
        $this->setDefaultParam( $arParams["SIDEBAR_WIDTH"], "");

        $this->setDefaultBooleanParam($arParams["SHOW_SORT"]);

        $this->setDefaultBooleanParam( $arParams["SHOW_PAGER"],true);
        $this->setDefaultParam( $arParams["PAGER_TYPE"],"LOAD_MORE");

        /*---------------------------------------------------------------------------------*/

        $this->setDefaultParam( $arParams["COUNT"],24);

        $this->setDefaultParam( $arParams["FILTER"],"");
        $this->setDefaultParam( $arParams["SORT_1"],"sort");
        $this->setDefaultParam( $arParams["BY_1"],"asc");
        $this->setDefaultParam( $arParams["SORT_2"],"active_from");
        $this->setDefaultParam( $arParams["BY_2"],"desc");

        $this->setDefaultParam($arParams["LIST_VIEW_TITLE_TEXT"],"");
        $this->setDefaultParam($arParams["LIST_VIEW_TITLE_TAG"],"div");
        $this->setDefaultParam($arParams["LIST_VIEW_TITLE_CSS"],"uk-h1");
        $this->setDefaultParam($arParams["LIST_VIEW_TITLE_ICON_CSS"],"");
        $this->setDefaultParam($arParams["LIST_VIEW_TITLE_ALIGN"],"left");

        $this->setDefaultParam($arParams["LIST_VIEW_WRAP_COLOR"],"default");
        $this->setDefaultParam($arParams["LIST_VIEW_WRAP_SIZE"],"small");
        $this->setDefaultParam($arParams["LIST_VIEW_WRAP_ADD_CSS"],"");

        $this->setDefaultParam($arParams["LIST_VIEW_CONTAINER_WIDTH_CSS"],"expand");
        $this->setDefaultParam($arParams["LIST_VIEW_CONTAINER_TYPE"],"TILE");
        $this->setDefaultParam($arParams["LIST_VIEW_CONTAINER_ELEMENT_WIDTH_CSS"],
            "uk-child-width-1-1 uk-child-width-1-2@m uk-child-width-1-3@l uk-child-width-1-4@xl");
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
        $this->setDefaultParam($arParams["SLIDER_AUTOPLAY"],"true");
        $this->setDefaultParam($arParams["SLIDER_AUTOPLAY_INTERVAL"],6000);
        $this->setDefaultParam($arParams["SLIDER_CENTERED"],"false");
        $this->setDefaultParam($arParams["SLIDER_MOVE_SETS"],"false");
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

        return $arParams;
    }

    /**
     * @return string
     */
    public function getPagerType() {
        return ($this->getParam("PAGER_TYPE") === "PAGES") ? "PAGES" : "LOAD_MORE";
    }

    /**
     * @return boolean
     */
    public  function  isPagerTypeLoadMore() {
        return ($this->getPagerType() === "LOAD_MORE");
    }
}