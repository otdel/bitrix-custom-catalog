<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

\CBitrixComponent::includeComponentClass("oip:iblock.element");

class COipIblockElementComplex extends \COipIblockElement
{

    /**
     * @return array
     * @param  $arParams
     */
    protected function initParams($arParams)
    {
        
        $arParams =  parent::initParams($arParams);

        $this->setDefaultBooleanParam($arParams["SEF_MODE"], true);
        $this->setDefaultParam($arParams["BASE_DIR"],"/");

        $this->setDefaultParam($arParams["SEF_URL_TEMPLATES"],[
            "index"    => "",
            "section" => "#SECTION_ID#/",
            "element" => "#SECTION_ID#/#ELEMENT_ID#/",
        ]);

        $this->setDefaultParam($arParams["VARIABLE_ALIASES"], [
            "index" => [],
            "section" => [],
            "element" => []
        ]);
        $this->setDefaultParam($arParams["SEF_VARIABLE_ALIASES"], $arParams["VARIABLE_ALIASES"]);

        $this->setDefaultParam($arParams["COMPONENT_VARIABLES"], ["IBLOCK_ID", "SECTION_ID", "ELEMENT_ID"]);

        return $arParams;
    }

    public function executeComponent()
    {
        if ($this->isParam("SEF_MODE")) {
            $componentPage = $this->executeWithSEF($this->getParam("COMPONENT_VARIABLES"));
        }
        else {
           $componentPage = $this->executeWithoutSEF($this->getParam("COMPONENT_VARIABLES"));
        }

        $this->IncludeComponentTemplate($componentPage);
    }

    private function executeWithSEF($arComponentVariables) {

        $arVariables = [];

        $componentPage = CComponentEngine::ParseComponentPath(
            $this->getParam("BASE_DIR"),
            $this->getParam("SEF_URL_TEMPLATES"),
            $arVariables
        );

        if (strlen($componentPage) <= 0) {
            $componentPage = "index";
        }

        CComponentEngine::InitComponentVariables(
            $componentPage,
            $arComponentVariables,
            $this->getParam("SEF_VARIABLE_ALIASES"),
            $arVariables);

        $this->arResult = [
            "BASE_DIR"      => $this->getParam("BASE_DIR"),
            "URL_TEMPLATES" =>$this->getParam("SEF_URL_TEMPLATES"),
            "VARIABLES"     => $arVariables,
            "ALIASES"       => $this->getParam("SEF_VARIABLE_ALIASES"),
        ];

        return $componentPage;
    }

    private function executeWithoutSEF($arComponentVariables) {

        $arVariables = [];

        CComponentEngine::InitComponentVariables(
            false,
            $arComponentVariables,
            $this->getParam("VARIABLE_ALIASES"),
            $arVariables
        );

        if (intval($arVariables["ELEMENT_ID"]) > 0) {
            $componentPage = "element";
        }
        elseif (intval($arVariables["SECTION_ID"]) > 0) {
            $componentPage = "section";
        }
        else {
            $componentPage = "index";
        }

        $this->arResult = [
            "VARIABLES"     => $arVariables,
            "ALIASES"       => $this->getParam("VARIABLE_ALIASES"),
        ];

        return $componentPage;
    }
}