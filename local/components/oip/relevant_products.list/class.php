<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\ArgumentException;
use \Bitrix\Main\ArgumentNullException;
use \Bitrix\Main\ArgumentTypeException;
use \Bitrix\Main\LoaderException;
use \Bitrix\Main\SystemException;
use Oip\CacheInfo;
use Oip\RelevantProducts\DataWrapper;

class COipRelevantProducts extends \CBitrixComponent
{
    /** @var CacheInfo $cacheInfo Информация о кеше внутри компонента */
    private $cacheInfo;

    public function onPrepareComponentParams($arParams)
    {
        return $this->initParams($arParams);
    }

    public function executeComponent()
    {
        if(empty($this->arResult["EXCEPTION"])) {
            try {
                $this->execute();
            } catch (LoaderException $e) {
                $this->arResult["EXCEPTION"] = $e->getMessage();
            }
            catch (SystemException $e) {
                $this->arResult["EXCEPTION"] = $e->getMessage();
            }
        }

        // Если требуется что-то вернуть после подключения компонента
//        if ($this->arParams) {
//            return array(
//                "SECTION_NAME" => $->getName(),
//                "UF_ELEMENTS_NUMBER" => $section->getPropValue("UF_ELEMENTS_NUMBER")
//            );
//        }
//        // Иначе просто подключаем шаблон
//        else {
            $this->includeComponentTemplate();
//        }

    }

    protected function execute() {
        $dataWrapper = new DataWrapper($this->cacheInfo);

        // Фильтры
        $filter = (!isset($this->arParams["FILTER"])) ? "TOP_SECTIONS" : $this->arParams["FILTER"];
        $this->arResult["FILTER"] = $filter;

        // В зависимости от выбранного фильтра, отдаем различные значения
        switch ($filter) {

            // Список популярных у юзера категорий
            case "FILTER_TOP_SECTIONS":
                // Получаем список просмотренных категорий (вместе с товарами)
                $viewedCategories = $dataWrapper->getViewedSections($this->arParams["USER_ID"]);
                if (!isset($viewedCategories)) {
                    $this->arResult["ERRORS"][] = "Не удалось получить информацию о просмотренных категориях.";
                    break;
                }
                // Отдаем в результирующий массив для использования в шаблоне
                $this->arResult["TOP_SECTIONS"] = $viewedCategories;
                break;

            // Полный список товаров (просмотренных) с фильтрацией по категориям
            case "FILTER_FULL_PRODUCTS_LIST":
                // Получаем список просмотренных категорий (вместе с товарами)
                $viewedSections = $dataWrapper->getViewedSections($this->arParams["USER_ID"]);
                if (!isset($viewedSections)) {
                    $this->arResult["ERRORS"][] = "Не удалось получить информацию о просмотренных товарах.";
                    break;
                }
                // Отдаем в результирующий массив для использования в шаблоне
                $this->arResult["FULL_PRODUCT_LIST"]  = $viewedSections;
                break;

            // Полный список товаров (просмотренных) с фильтрацией по категориям
            case "FILTER_TOP_SECTION_PRODUCTS":
                // Получаем список просмотренных категорий (вместе с товарами)
                $viewedCategories = $dataWrapper->getViewedSections($this->arParams["USER_ID"]);
                if (!isset($viewedCategories)) {
                    $this->arResult["ERRORS"][] = "Не удалось получить информацию о просмотренных товарах.";
                    break;
                }
                // Выбираем топ-1 категорию
                $topCategory = $viewedCategories[0];
                if (!isset($topCategory)) {
                    $this->arResult["ERRORS"][] = "Не удалось определить топ-категорию.";
                    break;
                }
                // Получаем список товаров по топ-1 категории (и просмотренные, и непросмотренные)
                $categoryProducts = $dataWrapper->getSectionProducts($this->arParams["USER_ID"], $topCategory->getId());
                if (count($categoryProducts) == 0) {
                    $this->arResult["ERRORS"][] = "Не удалось получить информацию о топ-категории.";
                    break;
                }
                // Отдаем топ категорию для использования в шаблоне
                $this->arResult["TOP_SECTION"] = array_shift($categoryProducts);
                break;

            // Список лайкнутых товаров
            case "FILTER_LIKED_PRODUCTS":
                // Получаем список просмотренных категорий (вместе с товарами)
                $viewedCategories = $dataWrapper->getViewedSections($this->arParams["USER_ID"]);
                // Чистим категории от товаров без лайков
                foreach ($viewedCategories as $key => $viewedCategory) {
                    foreach ($viewedCategory->getRelevantProducts() as $key2 => $product) {
                        if ($product->getLikesCount() == 0) {
                            unset($viewedCategory->getRelevantProducts()[$key2]);
                        }
                    }
                    // Чистим список товаров от категорий без лайков
                    if ($viewedCategory->getLikesCount() == 0 ) {
                        unset($viewedCategories[$key]);
                    }
                }
                // Отдаем в результирующий массив для использования в шаблоне
                $this->arResult["LIKED_PRODUCTS"]  = $viewedCategories;
                break;

            // Список самых просматриваемых категорий (без продуктов)
            case "FILTER_MOST_VIEWED_SECTIONS_LIST":
                // Получаем список самых просматриваемых категорий (без товаров внутри)
                $viewedCategories = $dataWrapper->getMostViewedCategories();
                // Отдаем в результирующий массив для использования в шаблоне
                $this->arResult["MOST_VIEWED_SECTIONS_LIST"] = $viewedCategories;
                break;

            // Список самых залайканных категорий (без продуктов)
            case "FILTER_MOST_LIKED_SECTIONS_LIST":
                // Получаем список самых просматриваемых категорий (без товаров внутри)
                $topCategories = $dataWrapper->getMostLikedCategories();
                // Отдаем в результирующий массив для использования в шаблоне
                $this->arResult["MOST_LIKED_SECTIONS_LIST"] = $topCategories;
                break;

            // Список самых просматриваемых товаров
            case "FILTER_MOST_VIEWED_PRODUCTS_LIST":
                // Получаем список самых просматриваемых товаров
                $topProducts = $dataWrapper->getMostViewedProducts();
                // Отдаем в результирующий массив для использования в шаблоне
                $this->arResult["MOST_VIEWED_PRODUCTS_LIST"] = $topProducts;
                break;

            // Список самых залайканных товаров
            case "FILTER_MOST_LIKED_PRODUCTS_LIST":
                // Получаем список самых залайканных товаров
                $topProducts = $dataWrapper->getMostLikedProducts();
                // Отдаем в результирующий массив для использования в шаблоне
                $this->arResult["MOST_LIKED_PRODUCTS_LIST"] = $topProducts;
                break;

            // Список новых товаров из топ-10 категорий пользователя
            case "FILTER_NEW_PRODUCTS_LIST":
                // Получаем список самых новых товаров во всех категориях
                $newProductCategories = $dataWrapper->getNewProductCategories();
                // Узнаем, какие категории популярны у пользователя
                $viewedCategories = $dataWrapper->getViewedSections($this->arParams["USER_ID"]);
                // Оставляем только 10 из них
                if (is_array($viewedCategories)) {
                    $viewedCategories = array_slice($viewedCategories, 0, 10);
                }
                // Чистим категории от тех, которые не популярны у пользователя
                foreach ($newProductCategories as $key => $newProductCategory) {
                    // Смотрим, есть ли данная категория в спсике топ-10 категорий пользователя
                    $categoryFound = false;
                    foreach ($viewedCategories as $topCategory) {
                        if ($newProductCategory->getId() == $topCategory->getId()) {
                            $categoryFound = true;
                            break;
                        }
                    }
                    // Удаляем категорию, если она не встретилась среди топ-10 пользовательских
                    if (!$categoryFound) {
                        unset($newProductCategories[$key]);
                    }
                }

                // Отдаем в результирующий массив для использования в шаблоне
                $this->arResult["NEW_PRODUCTS_LIST"] = $newProductCategories;
                break;
        }

    }

    /**
     * @param array $arParams
     * @throws ArgumentNullException | ArgumentTypeException | ArgumentException
     * @return array
     */
    protected function initParams($arParams) {
        try {
            // Проверка на валидность параметра "CACHE_TIME"
            if (is_set($arParams["CACHE_TIME"]) && !intval($arParams["CACHE_TIME"])) {
                throw new \Bitrix\Main\ArgumentTypeException("CACHE_TIME");
            }
            // Время жизни кеша
            $this->setDefaultParam($arParams["CACHE_TIME"], 60);
        }
        catch (\Bitrix\Main\ArgumentException $e) {
            $this->arResult["EXCEPTION"] = $e->getMessage();
        }

        // Кешировать выборки из БД. По умолчанию - "N"
        $this->setDefaultParam($arParams["CACHE"], "N");

        // Заполняем информацию о кешировании внутри компонента
        $this->cacheInfo = new CacheInfo(
            $arParams["CACHE"] == "Y",
            $arParams["CACHE_TIME"],
            $this->getCacheId()
        );

        return $arParams;
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
    protected function setDefaultBooleanParam(&$param, $defaultValue) {
        if(!is_set($param) || !is_bool($param)) {
            $param = $defaultValue;
        }
    }

    /**
     *
     * @param string $paramCode
     * @return mixed
     */
    public function getParam($paramCode) {
        return $this->getParamRecursive($paramCode, $this->arParams);
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

}