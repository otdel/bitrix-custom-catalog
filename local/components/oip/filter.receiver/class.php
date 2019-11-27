<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

\CBitrixComponent::includeComponentClass("oip:component");

use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\SystemException;

class COipFilterReceiver extends \COipComponent
{

    const PARAM_TYPE_FIELD = "f";
    const PARAM_TYPE_PROP = "p";
    const PARAM_TYPE_SORT = "s";


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
            return ("f".$componentId."_" === substr($key,0,3));
        },ARRAY_FILTER_USE_KEY);
    }

    /**
     * @param array $incomingParams
     * @return array
     */
    private function makeFinalFilter($incomingParams) {
        switch($this->getParam("MODE")) {
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
            $paramName = $this->getFinalFilterParamName($paramKey);
            $paramType = $this->getFinalFilterParamType($paramKey);

            if($paramType == self::PARAM_TYPE_PROP) {
                $paramName = "PROPERTY_".$paramName;
            }

            $finalIblockFilter[$paramName] = $this->getFinalFilterParamValue($paramValue);
        }

        return $finalIblockFilter;
    }

    /**
     * @param array $incomingParam
     * @return string
     */
    private function getFinalFilterParamName($incomingParam) {
        return substr(explode("_",$incomingParam)[1],1);
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
    private function getFinalFilterParamValue($incomingParamValue) {
        return explode(",", $incomingParamValue);
    }
}