<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

require_once(__DIR__."/../Section.php");

\CBitrixComponent::includeComponentClass("oip:iblock.section.list");

class COipIblockSectionOneName extends \COipIblockSectionList
{

    public function executeComponent()
    {
        if(empty($this->arResult["EXCEPTION"])) {
            try {
                if (!\Bitrix\Main\Loader::includeModule("iblock")) {
                    throw new \Bitrix\Main\SystemException("Module iblock is not installed");
                }

                $this->execute();

                $section = reset($this->arResult["SECTIONS"]);
                
                return ($section) ? $section->getName() : null;

            } catch (LoaderException $e) {
                return  $e->getMessage();
            }
            catch (SystemException $e) {
                return $e->getMessage();
            }
        }
        else {
            return $this->arResult["EXCEPTION"];
        }
    }
}
