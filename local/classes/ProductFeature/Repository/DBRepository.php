<?php


namespace Oip\ProductFeature\Repository;

use Bitrix\Main\Data\Cache;
use Oip\CacheInfo;
use Oip\IblockElementFeature\Feature\Feature;
use Oip\IblockElementFeature\Feature\FeatureCollection;
use Oip\IblockElementFeature\FeatureValue\FeatureValue;
use Oip\IblockElementFeature\FeatureValue\FeatureValueCollection;
use Oip\ProductFeature\ProductFeature;
use Oip\ProductFeature\ProductFeatureValue;
use Protobuf\Exception;

class DBRepository implements RepositoryInterface
{
    /** @var CacheInfo $cacheInfo Информация о кешировании внутри репозитория */
    private $cacheInfo;
    /** @var string $featureTableName Таблица с характеристиками товаров */
    private $featureTableName = 'oip_element_feature';
    /** @var string $featureValueTableName Таблица со значениями характеристик товаров */
    private $featureValueTableName = 'oip_element_feature_value';
    /** @var string $featurePredefinedValueTableName Таблица с предопределенными значениями товаров */
    private $featurePredefinedValueTableName = 'oip_element_feature_predefined_value';
    /** @var string $sectionFeatureTableName Таблица с настройками характеристик внутри раздела */
    private $sectionFeatureTableName = 'oip_section_feature';
    /** @var \CDatabase $db */
    private $db;

    /**
     * DBRepository constructor.
     * @param \CDatabase $db
     * @param $cacheInfo
     * @throws \Exception
     */
    public function __construct(\CDatabase $db, $cacheInfo = null)
    {
        if (!isset($cacheInfo)) $cacheInfo = new CacheInfo();

        $this->db = $db;
        $this->initFeatures();
        $this->cacheInfo = $cacheInfo;
    }

    /**
     * @inheritDoc
     */
    public function initFeatures()
    {
        // TODO: Вынести характеристики за класс, чтобы можно было использовать в любой реализации интерфейса
        /** @var ProductFeature[] $arFeatures */
        $arFeatures = array();
        // Базовые
        $arFeatures[] = new ProductFeature(["name" => "Цвет", "code" => "color"]);
        $arFeatures[] = new ProductFeature(["name" => "Гарантия", "code" => "guarantee"]);
        $arFeatures[] = new ProductFeature(["name" => "Модель кухни", "code" => "kitchenModelName"]);
        // Кухонные вытяжки
        $arFeatures[] = new ProductFeature(["name" => "Ширина", "code" => "width"]);
        $arFeatures[] = new ProductFeature(["name" => "Тип", "code" => "type"]);
        $arFeatures[] = new ProductFeature(["name" => "Производительность мотора (м3/ч)", "code" => "enginePerformance"]);
        $arFeatures[] = new ProductFeature(["name" => "Тип управления", "code" => "controlType"]);
        $arFeatures[] = new ProductFeature(["name" => "Режим функционирования", "code" => "functionalMode"]);
        $arFeatures[] = new ProductFeature(["name" => "Тип освещения", "code" => "lightingType"]);
        $arFeatures[] = new ProductFeature(["name" => "Диаметр выходного отверстия", "code" => "outerHoleDiameter"]);

        // Для каждого типа продукта инициализируем все его характеристики в БД
        //foreach ($arFeatures as $features) {
            foreach ($arFeatures as $key => $feature) {
                // Добавляем запись
                $sql = "ALTER TABLE {$this->featureTableName} AUTO_INCREMENT = 1; ";
                // Выполняем запрос
                $query = $this->db->Query($sql);

                $sql =
                    "INSERT IGNORE INTO {$this->featureTableName} (code, name, css_filter_classname, is_predefined, is_disabled) " .
                    "VALUES (" .
                    "   '" . $feature->getCode() ."', " .
                    "   '" . $feature->getName() ."', " .
                    "   '" . $feature->getCssFilterClassname() ."', " .
                    "   '" . ($feature->getIsPredefined() ? 1 : 0) . "', " .
                    "   '" . ($feature->getIsDisabled() ? 1 : 0) . "' " .
                    "); ";

                // Заменяем все переводы строк, приводя запрос в одну большую строку
                $sql = preg_replace("/[\r\n]*/","", $sql);
                // Выполняем запрос
                $query = $this->db->Query($sql);
                // Если запрос не выполнился
                if (!$query) throw new \Exception("Не удалось выполнить запрос на создание новой характеристики.");
            }
        //}
    }

    /**
     * @inheritDoc
     */
    public function getProductFeatures() {
        // Получаем экземпляр класса Cache, формируем ключ кеша для данного запроса
        $cache = Cache::createInstance();
        $cacheKey = $this->cacheInfo->getCacheKey() . ".getProductFeatures";
        // Если кеш есть и он включен
        if ($this->cacheInfo->isCacheEnabled() && $cache->initCache($this->cacheInfo->getCacheLifeTime(), $cacheKey)) {
            // Достаем переменные из кеша
            $vars = $cache->getVars();
            return unserialize($vars["productFeatures"]);
        }
        // Если кеша нет или он неактуален (или выключен)
        elseif ($cache->startDataCache()) {
            try {
                // Запрашиваем все характеристики товара и их значения
                $sql =
                    "SELECT " .
                    "   pf.id, " .
                    "   pf.code, " .
                    "   pf.name, " .
                    "   pf.css_filter_classname, " .
                    "   pf.is_predefined, " .
                    "   pf.is_disabled, " .
                    "   pf.date_insert, " .
                    "   pf.date_modify " .
                    "FROM {$this->featureTableName} pf ; ";

                // Выполняем запрос
                $query = $this->db->Query($sql);

                // Если запрос не выполнился
                if (!$query) {
                    throw new \Exception("Не удалось выполнить запрос на получение информации о характеристиках товаров.");
                }

                $result = array();

                while ($queryResult = $query->Fetch()) {
                    $result[] = $queryResult;
                }

                // Записываем в кеш
                $cache->endDataCache(array("productFeatures" => serialize($result)));

                return $result;
            }
            catch (Exception $ex) {
                throw new \Exception("Не удалось получить информацию о характеристиках товаров: " . $ex->getMessage());
            }
        }
    }

    /**
     * Получение значений характеристик для товаров по их идентификаторам
     *
     * @param int[] $arProductId Массив идентификаторов товаров
     * @return array | null
     * @throws \Exception
     */
    public function getProductFeatureValues($arProductId) {
        if (is_int($arProductId)) $arProductId = array($arProductId);
        if (is_array($arProductId) && !($arProductId === array_filter($arProductId,'is_int'))) {
            throw new \Exception("Входные данные имеют неверный формат.");
        }

        // Получаем экземпляр класса Cache, формируем ключ кеша для данного запроса
        $cache = Cache::createInstance();
        $cacheKey = $this->cacheInfo->getCacheKey() . ".getProductFeatures_" . md5(serialize(func_get_args()));
        // Если кеш есть и он включен
        if ($this->cacheInfo->isCacheEnabled() && $cache->initCache($this->cacheInfo->getCacheLifeTime(), $cacheKey)) {
            // Достаем переменные из кеша
            $vars = $cache->getVars();
            return unserialize($vars["productFeatureValues"]);
        }
        // Если кеша нет или он неактуален (или выключен)
        elseif ($cache->startDataCache()) {
            try {
                // Запрашиваем все характеристики товара и их значения
                $sql =
                    "SELECT " .
                    "   fv.id, " .
                    "   fv.element_id, " .
                    "   fv.feature_code, " .
                    "   fv.predefined_value_id, " .
                    "   fv.value, " .
                    "   fv.is_disabled, " .
                    "   fv.date_insert, " .
                    "   fv.date_modify, " .
                    "   COALESCE(sf.sort_info, 0) AS sort_info " .
                    "FROM {$this->featureValueTableName} fv " .
                    "LEFT JOIN {$this->featureTableName} f ON f.code = fv.feature_code " .
                    "LEFT JOIN b_iblock_element el ON el.id = fv.element_id " .
                    "LEFT JOIN {$this->sectionFeatureTableName} sf ON sf.feature_code = fv.feature_code AND sf.section_id = COALESCE(el.IBLOCK_SECTION_ID, el.IBLOCK_ID) " .
                    "WHERE fv.element_id IN (" . implode(',', $arProductId) . "); ";

                // Выполняем запрос
                $query = $this->db->Query($sql);

                // Если запрос не выполнился
                if (!$query) {
                    throw new \Exception("Не удалось выполнить запрос на получение характеристик товара.");
                }

                $result = array();

                while ($queryResult = $query->Fetch()) {
                    $result[] = $queryResult;
                }

                // Записываем в кеш
                $cache->endDataCache(array("productFeatureValues" => serialize($result)));

                return $result;
            } catch (Exception $ex) {
                throw new \Exception("Не удалось получить список характеристик товара: " . $ex->getMessage());
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function setProductFeatureValues($productId, $featuresToSet)
    {
        if (!isset($featuresToSet) || count($featuresToSet) == 0) return;

        // Удаляем все старые значения
        $query = $this->db->Query(
            "DELETE FROM {$this->featureValueTableName} " .
            "WHERE element_id = '{$productId}' AND feature_code IN ('" . implode('\',\'', array_keys($featuresToSet)) . "');"
        );
        // Если запрос не выполнился
        if (!$query) throw new \Exception("Не удалось выполнить запрос на удаление конкретных характеристик товара (перед обновлением).");

        // Проставляем характеристики
        foreach ($featuresToSet as $featureCode => $featureValues) {
            if (!is_array($featureValues)) {
                $featureValues = array($featureValues);
            }

            // Если значения не переданы
            if (count($featureValues) == 0) continue;

            // Формируем SQL со значениями
            $arValuesSql = array();
            foreach ($featureValues as $featureValue) {
                $arValuesSql[] = "VALUE ('{$productId}', '{$featureCode}', '{$featureValue}')";
            }
            // Добавляем записи
//                $query = $this->db->Query(
//                    "INSERT INTO {$this->featureValueTableName} (element_id, feature_code, value) " .
//                    "VALUES ('{$productId}', '{$featureCode}', '{$featureValue}'); "
//                );
            $query = $this->db->Query(
                "INSERT INTO {$this->featureValueTableName} (element_id, feature_code, value) " .
                implode(',', $arValuesSql) .
                "; "
            );
            // Если запрос не выполнился
            if (!$query) throw new \Exception("Не удалось выполнить запрос на установку характеристик для товара.");
        }
    }

    /**
     * Получение объекта ProductFeature из ORM-овского Feature
     *
     * @param Feature $elementFeature
     * @return ProductFeature
     */
    private function FeatureToProductFeature($elementFeature) {
        $productFeature = new ProductFeature();
        $productFeature->setId($elementFeature->getId());
        $productFeature->setName($elementFeature->getName());
        $productFeature->setSortFilter($elementFeature->getSortFilter());
        $productFeature->setSortInfo($elementFeature->getSortInfo());
        $productFeature->setCssFilterClassname($elementFeature->getCssFilterClassname());
        $productFeature->setIsFilter($elementFeature->getIsFilter());
        $productFeature->setIsPredefined($elementFeature->getIsPredefined());
        $productFeature->setIsDisabled($elementFeature->getIsDisabled());
        $productFeature->setDateInsert($elementFeature->getDateInsert());
        $productFeature->setDateModify($elementFeature->getDateModify());
        return $productFeature;
    }

    /**
     * @inheritDoc
     */
    public function createProperty(ProductFeature $productFeature) {
        // Добавляем запись в таблицу просмотров товара
        $sql =
            "INSERT INTO {$this->featureTableName} (name, sort_filter, sort_info, css_filter_classname, is_filter, is_predefined, is_disabled) " .
            "VALUES (" .
            "   '" . $productFeature->getName() ."', " .
            "   '" . $productFeature->getSortFilter() ."', " .
            "   '" . $productFeature->getSortInfo() ."', " .
            "   '" . $productFeature->getCssFilterClassname() ."', " .
            "   '" . ($productFeature->getIsFilter() ? 1 : 0) . "', " .
            "   '" . ($productFeature->getIsDisabled() ? 1 : 0) . "' " .
            ");";

        // Выполняем запрос
        $query = $this->db->Query($sql);
        // Если запрос не выполнился
        if (!$query) throw new \Exception("Не удалось выполнить запрос на создание новой характеристики.");
        // Если запись не была добавлена
        if ($query->AffectedRowsCount() == 0) throw new \Exception("Не удалось добавить запись с идентификатором нового гостевого пользователя.");
        // Возвращаем идентификатор созданной характеристики
        return $this->db->LastID();
    }

    /**
     * @param FeatureCollection $featureCollection
     * @return array
     */
    private function FeatureCollectionToProductFeatureArray($featureCollection) {
        $resultFeatures = array();
        foreach ($featureCollection as $feature) {
            $resultFeatures[] = $this->FeatureToProductFeature($feature);
        }
        return $resultFeatures;
    }

    /**
     * @param FeatureValue $elementFeatureValue
     * @return ProductFeatureValue
     */
    private function FeatureValueToProductFeature($elementFeatureValue) {
        $productFeatureValue = new ProductFeatureValue();
        $productFeatureValue->setId($elementFeatureValue->getId());
        $productFeatureValue->setProductId($elementFeatureValue->getElementId());
        $productFeatureValue->setValue($elementFeatureValue->getValue());
        $productFeatureValue->setPredefinedValueId($elementFeatureValue->getPredefinedValueId());
        $productFeatureValue->setIsDisabled($elementFeatureValue->getIsDisabled());
        $productFeatureValue->setDateInsert($elementFeatureValue->getDateInsert());
        $productFeatureValue->setDateModify($elementFeatureValue->getDateModify());
        return $productFeatureValue;
    }

    /**
     * @param FeatureValueCollection $featureValueCollection
     * @return array
     */
    private function FeatureValueCollectionToProductFeatureArray($featureValueCollection) {
        $resultFeatureValues = array();
        foreach ($featureValueCollection as $featureValue) {
            $resultFeatureValues[] = $this->FeatureValueToProductFeature($featureValue);
        }
        return $resultFeatureValues;
    }

    /**
     * Получение значений характеристик для товаров по их идентификаторам
     *
     * @param int[] $arSectionId Массив идентификаторов разделов
     * @return array | null
     * @throws \Exception
     */
    public function getSectionFeatureOptions($arSectionId) {
        if (is_int($arSectionId)) $arSectionId = array($arSectionId);
        if (is_array($arSectionId) && !($arSectionId === array_filter($arSectionId,'is_numeric'))) {
            throw new \Exception("Входные данные имеют неверный формат.");
        }

        // Получаем экземпляр класса Cache, формируем ключ кеша для данного запроса
        $cache = Cache::createInstance();
        $cacheKey = $this->cacheInfo->getCacheKey() . ".getSectionFeatureOptions" . md5(serialize(func_get_args()));
        // Если кеш есть и он включен
        if ($this->cacheInfo->isCacheEnabled() && $cache->initCache($this->cacheInfo->getCacheLifeTime(), $cacheKey)) {
            // Достаем переменные из кеша
            $vars = $cache->getVars();
            return unserialize($vars["sectionFeatureOptions"]);
        }
        // Если кеша нет или он неактуален (или выключен)
        elseif ($cache->startDataCache()) {
            try {
                // Запрашиваем все настройки характеристик
                $sql =
                    "SELECT " .
                    "   sf.id, " .
                    "   sf.section_id, " .
                    "   sf.feature_code, " .
                    "   f.name as feature_name, " .
                    "   sf.is_filter, " .
                    "   sf.is_info, " .
                    "   sf.is_disabled, " .
                    "   sf.sort_filter, " .
                    "   sf.sort_info, " .
                    "   sf.date_insert, " .
                    "   sf.date_modify " .
                    "FROM {$this->sectionFeatureTableName} sf " .
                    "LEFT JOIN {$this->featureTableName} f ON f.code = sf.feature_code " .
                    "WHERE sf.section_id IN (" . implode(',', $arSectionId) . "); ";

                // Выполняем запрос
                $query = $this->db->Query($sql);

                // Если запрос не выполнился
                if (!$query) {
                    throw new \Exception("Не удалось выполнить запрос на получение настроек характеристик внутри категории(-й).");
                }

                $result = array();

                while ($queryResult = $query->Fetch()) {
                    $result[] = $queryResult;
                }

                // Записываем в кеш
                $cache->endDataCache(array("sectionFeatureOptions" => serialize($result)));

                return $result;
            } catch (Exception $ex) {
                throw new \Exception("Не удалось получить список настроек характеристик: " . $ex->getMessage());
            }
        }
    }

    /**
     * Получение уникальных значений для характеристики по ее коду
     *
     * @param string $featureCode Код характеристики
     * @return array | null
     * @throws \Exception
     */
    public function getFeatureDistinctValues($featureCode) {
        // Получаем экземпляр класса Cache, формируем ключ кеша для данного запроса
        $cache = Cache::createInstance();
        $cacheKey = $this->cacheInfo->getCacheKey() . ".getFeatureDistinctValues" . md5(serialize(func_get_args()));
        // Если кеш есть и он включен
        if ($this->cacheInfo->isCacheEnabled() && $cache->initCache($this->cacheInfo->getCacheLifeTime(), $cacheKey)) {
            // Достаем переменные из кеша
            $vars = $cache->getVars();
            $result = unserialize($vars["featureDistinctValues"]);
        }
        // Если кеша нет или он неактуален (или выключен)
        elseif ($cache->startDataCache()) {
            // Для каждой карактеристики получаем distinct значения
            $sql =
                "SELECT DISTINCT efv.value AS val
             FROM oip_element_feature_value efv 
             WHERE efv.feature_code = '{$featureCode}'; ";

            // Выполняем запрос
            $query = $this->db->Query($sql);

            // Если запрос не выполнился
            if (!$query) {
                throw new \Exception("Не удалось выполнить запрос на получение списка значений для характеристики " . $featureCode);
            }

            // Формируем результирующий массив на основе пришедших данных
            $result = array();
            while ($queryResult = $query->Fetch()) {
                $result[] = $queryResult["val"];
            }

            // Записываем в кеш
            $cache->endDataCache(array("featureDistinctValues" => serialize($result)));
        }

        return $result;
    }

    /**
     * Получение списка элементов, удовлетворяющих набору фильтров
     *
     * @param array $filters Код характеристики
     * @param int $limit Ограничение количества
     * @param int $offset Смещение
     * @return array | null
     * @throws \Exception
     */
    public function getFilteredElements($filters, $limit = 10000, $offset = 0)
    {
        // Получаем экземпляр класса Cache, формируем ключ кеша для данного запроса
        $cache = Cache::createInstance();
        $cacheKey = $this->cacheInfo->getCacheKey() . ".getFilteredElements" . md5(serialize(func_get_args()));
        // Если кеш есть и он включен
        if ($this->cacheInfo->isCacheEnabled() && $cache->initCache($this->cacheInfo->getCacheLifeTime(), $cacheKey)) {
            // Достаем переменные из кеша
            $vars = $cache->getVars();
            return unserialize($vars["filteredElements"]);
        } // Если кеша нет или он неактуален (или выключен)
        elseif ($cache->startDataCache()) {
            // Формируем WHERE условие, основанное на фильтрах
            $whereConditions = array();
            foreach ($filters as $key => &$filter) {
                foreach ($filter as &$value) {
                    $value = $this->db->ForSql($value);
                }
                // Если для характеристики указаны min и max - это диапазон
                if (array_key_exists('min', $filter) && array_key_exists('max', $filter)) {
                    if (($filter['real_min'] == $filter['min'] && $filter['real_max'] == $filter['max'])) {
                        // Если фильтруемый диапазон совпадает с полным диапазоном значений - пропускаем условие
                        continue;
                    }
                    $sqlWhere =
                        "fv.element_id IN ( " .
                        "   SELECT element_id " .
                        "   FROM {$this->featureValueTableName} " .
                        "   WHERE feature_code = '{$key}' AND value BETWEEN {$filter['min']} AND {$filter['max']} " .
                        ")";
                    //$whereConditions[] = $this->db->ForSql($sqlWhere);
                    $whereConditions[] = $sqlWhere;
                } else {
                    $sqlWhere =
                        "fv.element_id IN ( " .
                        "   SELECT element_id " .
                        "   FROM {$this->featureValueTableName} " .
                        "   WHERE feature_code = '{$key}' AND value IN ('" . implode("','", $filter) . "') " .
                        ")";
                    // Экранируем строку и добавляем в массив where условий
                    //$whereConditions[] = $this->db->ForSql($sqlWhere);
                    $whereConditions[] = $sqlWhere;
                }
            }

            // Строим запрос
            $sql =
                "SELECT DISTINCT fv.element_id " .
                "FROM {$this->featureValueTableName} fv " .
                (count($whereConditions) > 0 ? "WHERE " . implode(" AND ", $whereConditions) : "") . " " .
                "LIMIT {$limit} " .
                "OFFSET {$offset}; ";

            echo "<pre>";
            var_dump($sql);
            echo "</pre>";

            // Выполняем запрос
            $query = $this->db->Query($sql);

            // Если запрос не выполнился
            if (!$query) {
                throw new \Exception("Не удалось выполнить запрос на получение списка отфильтрованных элементов.");
            }

            // Формируем результирующий массив на основе пришедших данных
            $result = array();
            while ($queryResult = $query->Fetch()) {
                $result[] = $queryResult;
            }

            // Записываем в кеш
            $cache->endDataCache(array("filteredElements" => serialize($result)));

            return $result;
        }
    }

}