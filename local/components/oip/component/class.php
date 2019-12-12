<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Data\Cache;

abstract class COipComponent extends \CBitrixComponent
{

    const BOOL_PARAMS_NEGATIVE_VALUES = ["N"];

    protected $componentId;

    /** @var Cache $cache */
    protected $cache;

    public function __construct(?CBitrixComponent $component = null)
    {
        parent::__construct($component);

        $this->cache = Cache::createInstance();
    }

    public function onPrepareComponentParams($arParams)
    {
        return $this->initParams($arParams);
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
     * @param mixed $paramOrig
     */
    protected function setDefaultParam(&$param, $defaultValue, &$paramOrig = null) {


        $paramOrig = $defaultValue;

        if(!is_set($param)) {
            $param = $defaultValue;
        }

    }

    /**
     * @param string $paramCode
     * @return boolean
     */
    public function isParamDefault($paramCode) {
        return ($this->getParam($paramCode) == $this->getParam("_".$paramCode));
    }

    /**
     * @param string $componentParamName
     * @param mixed $valueToRewrite
     * @param boolean $boolTypeParam
     */
    public function rewriteComponentParams($componentParamName, $valueToRewrite, $boolTypeParam = false): void {
        if(!$valueToRewrite) {
           return;
        }

        $value = $valueToRewrite;

        if($boolTypeParam) {
            if($valueToRewrite && !in_array($valueToRewrite,self::BOOL_PARAMS_NEGATIVE_VALUES)) {
                $value = "Y";
            }
            else {
                $value = "N";
            }
        }

        if($this->isParamDefault($componentParamName)) {
            $this->setParam($componentParamName, $value);
        }
    }

    /**
     * @return int
    */
    public function getComponentId() {
        return $this->componentId;
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
     * @param string $paramValue
     * @return mixed
     */
    public function setParam($paramCode, $paramValue) {
        $this->arParams[$paramCode] = $paramValue;
        return $this->arParams[$paramCode];
    }

    /**
     * @param string $paramCode
     * @return boolean
     */
    public function isParam($paramCode) {
        return ($this->getParam($paramCode) === "Y") ? true : false;
    }

    /**
     * @return boolean
     */
    public function isCache() {
        return ($this->getParam("IS_CACHE") === "N") ? false : true;
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

        $this->setDefaultParam($arParams["CACHE_TIME"],300);
        $this->setDefaultBooleanParam( $arParams["IS_CACHE"],true);
        return $arParams;
    }

    protected function execute() {}

    public function executeComponent() {
        return parent::executeComponent();
    }

    /**
     * @param callable $fetchFunction
     * @param string $cacheId
     * @return mixed
     */
    public function cacheService(callable $fetchFunction, $cacheId) {

        $result = null;

        if($this->cache->initCache($this->getParam("CACHE_TIME"), $cacheId)) {
            $result = $this->cache->getVars();
        }
        elseif($this->cache->startDataCache()) {
            $result = $fetchFunction();
            $this->cache->endDataCache($result);
        }

        return $result;
    }

    /**
     * @param string $value
     * @param array $words
     * @param boolean $show
     * 
     * @return string
     */
    public function getNumWord($value, $words, $show = false)
    {
        $num = $value % 100;
        if ($num > 19) {
        $num = $num % 10;
        }

        $out = ($show) ?  $value . ' ' : '';
        switch ($num) {
            case 1:  $out .= $words[0]; break;
            case 2:
            case 3:
            case 4:  $out .= $words[1]; break;
            default: $out .= $words[2]; break;
        }

        return $out;
    }
}