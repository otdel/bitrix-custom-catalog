<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\ArgumentNullException;
USE Bitrix\Main\Application;

\CBitrixComponent::includeComponentClass("oip:component");

class CSorterForm extends \COipComponent {

    protected function initParams($arParams)
    {
        try {
            if(!is_set($arParams["FILTER_ID"])) {
                throw new ArgumentNullException("FILTER_ID");
            }
        }
        catch (ArgumentNullException $exception) {
            $this->arResult["EXCEPTION"] = $exception;
        }

        return $arParams;
    }

    public function executeComponent()
    {

        if(!$this->arResult["EXCEPTION"]) {
            $filterId = (int)$this->arParams["FILTER_ID"];

            $dateSortName = "f" . $filterId . "_screated";
            $ratingSortName = "f" . $filterId . "_sRating";
            $recommendSortName = "f" . $filterId . "_sRecommend";

            $request = Application::getInstance()->getContext()->getRequest();
            $dateSort = $request->get($dateSortName);
            $ratingSort = $request->get($ratingSortName);
            $recommendSort = $request->get($recommendSortName);

            $activeSortLabel = ($dateSort) ? "Новые первыми" : (($ratingSort) ? "По популярности" : (($recommendSort) ? "По рекомендациям" : "Выберите сортировку"));

            $this->arResult = [
                "DATE_SORT" => $dateSort,
                "RATING_SORT" => $ratingSort,
                "RECOMMEND_SORT" => $recommendSort,
                "ACTIVE_SORT_LABEL" => $activeSortLabel
            ];
        }

        $this->includeComponentTemplate();
    }
}