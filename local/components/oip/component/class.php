<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use \Bitrix\Main\SystemException;

abstract class COipComponent extends \CBitrixComponent
{

    protected $componentId;

    public function onPrepareComponentParams($arParams)
    {
        try {
            return $this->initParams($arParams);
        }
        catch (SystemException $e) {
            $this->arResult["EXCEPTION"] = $e->getMessage();
        }

        return $arParams;
    }

    final protected function initComponentId() {
        if(!is_set($GLOBALS["oipBitrixComponentIds"])) {
            $GLOBALS["oipBitrixComponentIds"] = [];
        }

        $lastComponentId =  (!empty($GLOBALS["oipBitrixComponentIds"])) ? max($GLOBALS["oipBitrixComponentIds"]) : 0;

        $this->componentId = $lastComponentId + 1;
        $GLOBALS["oipBitrixComponentIds"][] = $this->componentId;
    }

    /**
     * @param mixed $param
     * @param mixed $defaultValue
     */
    protected function setDefaultParam(&$param, $defaultValue) {

        if(!is_set($param)) {
            $param = $defaultValue;
        }

    }

    /**
     * @param mixed $param
     * @param boolean $defaultValue
     */
    protected function setDefaultBooleanParam(&$param, $defaultValue = false) {

        switch($defaultValue) {
            case true:
                if(!is_set($param) || $param !== "N") {
                    $param = "Y";
                }
                break;

            default:
                if(!is_set($param) || $param !== "Y") {
                    $param = "N";
                }
                break;
        }

    }

    /** @return  array */
    public function getParams() {
        return $this->arParams;
    }

    /**
     * @param string $paramCode
     * @return mixed
     */
    public function getParam($paramCode) {
        return $this->getParamRecursive($paramCode, $this->arParams);

    }

    /**
     * @param string $paramCode
     * @return boolean
     */
    public function isParam($paramCode) {
        return ($this->getParam($paramCode) === "Y") ? true : false;
    }

    /**
     * @param string $paramCode
     * @param array $arParams
     * @return mixed
     */
    protected function getParamRecursive($paramCode, $arParams) {

        $param = null;

        foreach ($arParams as $paramName => $paramValue) {

            if($paramName === $paramCode) {
                $param = $paramValue;
                break;
            }
            elseif(is_array($paramValue)) {
                $param = $this->getParamRecursive($paramCode, $paramValue);

                if($param) break;
            }
        }


        return $param;
    }

    /**
     * @param $arParams
     * @return array
     */
    protected function initParams($arParams) {
        $this->initComponentId();

        return $arParams;
    }

    protected function execute() {}

    public function executeComponent() {
        return parent::executeComponent();
    }
}