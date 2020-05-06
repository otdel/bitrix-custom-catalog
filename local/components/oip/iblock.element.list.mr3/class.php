<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

require_once(__DIR__."/../Element.php");
require_once(__DIR__."/../Property.php");
require_once(__DIR__."/../ReturnedData.php");

\CBitrixComponent::includeComponentClass("oip:iblock.element.list");

class COipIblockElementListMR3 extends \COipIblockElementList
{

    /**
     * @param \CIblockResult $iblockResult
     * @param string|array $propIds
     * @return array
     */
    protected function getRows($iblockResult, $propIds) {
        $arResult = [];

        while ($object = $iblockResult->GetNextElement()) {
            $result["FIELDS"] = $object->GetFields();

            if(!empty($propIds)) {
                if(is_string($propIds)) {
                    $result["PROPS"] = $object->GetProperties();
                }
                else {
                    $result["PROPS"] = $object->GetProperties([],["ID" => $propIds]);
                }
            }

            $arResult["ITEMS"][] = $result;
        }

        // TODO: В этом месте оставлять по одному товару для каждого wareId
        $arResult["ITEMS"] = $this->groupElementsByWareId($arResult["ITEMS"]);

        $arResult["PAGINATION"]["NAV_ID"] = $this->componentId;
        $arResult["PAGINATION"]["PAGES"] = $iblockResult->NavPageCount;
        $arResult["PAGINATION"]["PAGE"] = $iblockResult->NavPageNomer;
        $arResult["PAGINATION"]["PAGE_SIZE"] = $iblockResult->NavPageSize;
        $arResult["PAGINATION"]["RECORDS_COUNT"] = (float) $iblockResult->NavRecordCount;

        return $arResult;
    }

    /**
     * Группирование элементов по идентфиикатору товара wareId
     * @param $elements
     */
    private function groupElementsByWareId($elements) {
        $resultElements = array();
        // $arResult["ITEMS"]["PROPS"]["WARE_ID"]["VALUE"]
        foreach ($elements as $element) {
            // Проверяем, есть ли уже такой ware_id в результирующем наборе данных
            $wareId = $element["PROPS"]["WARE_ID"]["VALUE"];
            if (!isset($resultElements[$wareId])) {
                $resultElements[$wareId] = $element;
            }
        }
        return $resultElements;
    }



}