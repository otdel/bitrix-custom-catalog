<?php


namespace Oip\ProductFeature\Repository;

use Bitrix\Main\Application;
use Bitrix\Main\SystemException;
use Oip\IblockElementFeature\ElementFeature\ElementFeatureTable;
use Oip\IblockElementFeature\ElementFeatureValue\ElementFeatureValueTable;
use Oip\IblockElementFeature\Feature\Feature;
use Oip\IblockElementFeature\Feature\FeatureCollection;
use Oip\IblockElementFeature\Feature\FeatureTable;
use Oip\IblockElementFeature\FeatureValue\FeatureValue;
use Oip\IblockElementFeature\FeatureValue\FeatureValueCollection;
use Oip\IblockElementFeature\FeatureValue\FeatureValueTable;
use Oip\Mr3Sync\Ware\Ware;
use Oip\Mr3Sync\Ware\WareCookerHood;
use Oip\ProductFeature\ProductFeature;
use Oip\ProductFeature\ProductFeatureValue;
use Protobuf\Exception;

class DBRepository implements RepositoryInterface
{

    /** @var string $featureTableName Таблица с характеристиками товаров */
    private $featureTableName = 'oip_element_feature';
    /** @var string $featureValueTableName Таблица со значениями характеристик товаров */
    private $featureValueTableName = 'oip_element_feature_value';
    /** @var string $featurePredefinedValueTableName Таблица с предопределенными значениями товаров */
    private $featurePredefinedValueTableName = 'oip_element_feature_predefined_value';

    /** @var \CDatabase $db */
    private $db;

    /**
     * DBRepository constructor.
     * @param \CDatabase $db
     */
    public function __construct(\CDatabase $db)
    {
        $this->db = $db;
        $this->initFeatures();
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
                $sql =
                    "INSERT IGNORE INTO {$this->featureTableName} (code, name, sort_filter, sort_info, css_filter_classname, is_filter, is_predefined, is_disabled) " .
                    "VALUES (" .
                    "   '" . $feature->getCode() ."', " .
                    "   '" . $feature->getName() ."', " .
                    "   '" . $feature->getSortFilter() ."', " .
                    "   '" . $feature->getSortInfo() ."', " .
                    "   '" . $feature->getCssFilterClassname() ."', " .
                    "   '" . ($feature->getIsFilter() ? 1 : 0) . "', " .
                    "   '" . ($feature->getIsPredefined() ? 1 : 0) . "', " .
                    "   '" . ($feature->getIsDisabled() ? 1 : 0) . "' " .
                    ");";

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
        try {
            // Запрашиваем все характеристики товара и их значения
            $sql =
                "SELECT " .
                "   pf.id, " .
                "   pf.code, " .
                "   pf.name, " .
                "   pf.sort_filter, " .
                "   pf.sort_info, " .
                "   pf.css_filter_classname, " .
                "   pf.is_filter, " .
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

            return $result;
        }
        catch (Exception $ex) {
            throw new \Exception("Не удалось получить информацию о характеристиках товаров: " . $ex->getMessage());
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
                "   fv.date_modify " .
                "FROM {$this->featureValueTableName} fv " .
                "LEFT JOIN {$this->featureTableName} f ON f.code = feature_code " .
                "WHERE element_id IN (" . implode(',', $arProductId) . "); ";

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

            return $result;
        }
        catch (Exception $ex) {
            throw new \Exception("Не удалось получить список характеристик товара: " . $ex->getMessage());
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

}