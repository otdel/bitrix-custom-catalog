<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Oip\Custom\Component\Iblock\Element;

\CBitrixComponent::includeComponentClass("oip:iblock.element.list");

class COipIblockElementOne extends COipIblockElementList {

    public function executeComponent()
    {
        $this->execute();

        if(empty($this->rawData)) {
            $this->arResult["ERRORS"][] = "Ошибка: элемент не найден";
        }
        else {
            $this->arResult["ELEMENT"] = new Element(reset($this->rawData));
        }

        $this->includeComponentTemplate();


        return ($this->arResult["ELEMENT"]) ? $this->arResult["ELEMENT"]->getId() : null;
    }

    /**
     * @inheritdoc
    */
    protected function initParams($arParams)
    {
        $arParams = parent::initParams($arParams);

        try {
            if(!is_set($arParams["ELEMENT_ID"])) {
                throw new \Bitrix\Main\ArgumentNullException("ELEMENT_ID");
            }

            if(!intval($arParams["ELEMENT_ID"])) {
                throw new \Bitrix\Main\ArgumentTypeException("ELEMENT_ID");
            }
        }
        catch (\Bitrix\Main\ArgumentException $e) {
            $this->arResult["EXCEPTION"] = $e->getMessage();
        }

        return $arParams;
    }

    /**
     * @inheritdoc
     */
    protected function consistFilter() {
        $filter = parent::consistFilter();

        $filter["ID"] = $this->arParams["ELEMENT_ID"];

        if($this->arParams["SECTION_ID"]) {
           unset($filter["SECTION_ID"]);
        }

        return $filter;
    }
}