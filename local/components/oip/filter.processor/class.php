<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

\CBitrixComponent::includeComponentClass("oip:component");

use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\SystemException;

class COipFilterProcessor extends \COipComponent
{

    const PARAM_TYPE_FIELD = "f";
    const PARAM_TYPE_PROP = "p";
    const PARAM_TYPE_SORT = "s";
    const RANGE_VALUE_MODE_FIELDS = [
      "NAME"
    ];


    protected function initParams($arParams)
    {
        $arParams =  parent::initParams($arParams);

        if(!is_set($arParams["FILTER_ID"])) {
            throw new ArgumentNullException("FILTER_ID");
        }

        $this->setDefaultParam($arParams["SOURCE"],"get");
        $this->setDefaultParam($arParams["MODE"],"iblock");

        return $arParams;
    }

    /** @return array|null */
    public function executeComponent()
    {
        if(!$this->arResult["EXCEPTION"]) {
            try {

                $params = $this->getCurrentParams();
                return $this->makeFinalFilter($params);

            } catch (SystemException $exception) {

                $this->arResult["EXCEPTION"] = $exception->getMessage();
                $this->includeComponentTemplate();

                return null;
            }
        }
        else {
            $this->includeComponentTemplate();
        }
    }

    /** @return  array */
    private function getSource() {

        switch ($this->getParam("SOURCE")) {

            default:
                return $this->getGetSource();
            break;
        }

    }

    /** @return array */
    private function getGetSource() {
        return Bitrix\Main\Application::getInstance()->getContext()->getRequest()->getQueryList()->toArray();
    }

    /**
     * @return array
     */
    private function getCurrentParams() {
        $allParams = $this->getSource();
        return $this->getByCondition($allParams);
    }

    /**
     * @param array $allParams
     * @return array
     */
    private function getByCondition($allParams) {
        $componentId = $this->getParam("FILTER_ID");
        return array_filter($allParams, function($key) use($componentId) {
            return ("f".$componentId === explode("_", $key)[0]);
        },ARRAY_FILTER_USE_KEY);
    }

    /**
     * @param array $incomingParams
     * @return array
     */
    private function makeFinalFilter($incomingParams) {
        switch($this->getParam("MODE")) {

            case "TEMPLATE":
                return $this->makeTemplateFilter($incomingParams);
            break;

            default:
                return $this->makeIblockFilter($incomingParams);
            break;
        }
    }

    /**
     * @param array $incomingParams
     * @return array
     */
    private function makeIblockFilter($incomingParams) {
        $finalIblockFilter = [];

        foreach ($incomingParams as $paramKey => $paramValue) {
            $paramName = $this->getIblockFilterParamName($paramKey);
            $paramType = $this->getFinalFilterParamType($paramKey);

            if($paramType == self::PARAM_TYPE_PROP) {
                $paramName = "PROPERTY_".$paramName;
            }

            if($paramType == self::PARAM_TYPE_SORT) {
                $finalIblockFilter["BY_1"] = $paramValue;
                $finalIblockFilter["SORT_1"] = $paramName;
            }

            if($paramName == "SECTION_ID") {
                $finalIblockFilter["INCLUDE_SUBSECTIONS"] = "Y";
            }

            $finalIblockFilter[$paramName] = $this->getIblockFilterParamValue($this->replaceRangeValueMode($paramName, $paramValue));
        }

        return $finalIblockFilter;
    }

    private function makeTemplateFilter($incomingParams) {
        $activeTemplateParams = [];

        foreach ($incomingParams as $paramKey => $paramValue) {
            $activeTemplateParams[$paramKey] = explode(",",$paramValue);
        }

        return $activeTemplateParams;
    }

    /**
     * @param array $incomingParam
     * @return string
     */
    private function getIblockFilterParamName($incomingParam) {
        return $this->replaceSeparateIblockFilterParamName(substr(explode("_",$incomingParam)[1],1))
        ;
    }

    /**
     * @param string $paramName
     * @return string
     */
    private function replaceSeparateIblockFilterParamName($paramName) {
        return str_replace("-","_", $paramName);
    }

    /**
     * @param string $paramName
     * @return string
     */
    private function replaceRangeValueMode($paramName, $paramValue) {
        return ($this->isRangeValueMode($paramName)) ? "%".$paramValue."%" : $paramValue;
    }

    /**
     * @param string $paramName
     * @return boolean
     */
    private function isRangeValueMode($paramName) {
        return in_array($paramName, self::RANGE_VALUE_MODE_FIELDS);
    }


    /**
     * @param array $incomingParam
     * @return string
     */
    private function getFinalFilterParamType($incomingParam) {
        return substr(explode("_",$incomingParam)[1],0,1);
    }

    /**
     * @param array $incomingParamValue
     * @return array
     */
    private function getIblockFilterParamValue($incomingParamValue) {
        return explode(",", $incomingParamValue);
    }
}