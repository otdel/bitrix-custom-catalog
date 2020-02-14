<?php
namespace Oip\RelevantProducts;

use Bitrix\Main\Data\Cache;
use Oip\CacheInfo;
use Oip\RelevantProducts\Config\Configuration;
use Oip\RelevantProducts\RelevantProduct;

class DBDataSource implements DataSourceInterface
{
    /** @var \CDatabase $db */
    private $db;
    /** @var string $productsTableName Название таблицы товаров */
    private $productsTableName = 'b_iblock_element';
    /** @var string $productViewTableName Название таблицы с историей просмотра товаров */
    private $productViewTableName = 'oip_product_view';
    /** @var CacheInfo $cacheInfo */
    private $cacheInfo;

    /**
     * DBDataSource constructor.
     * @param \CDatabase $db
     * @param CacheInfo $cacheInfo
     */
    public function __construct(\CDatabase $db, CacheInfo $cacheInfo = null) {
        if (!isset($cacheInfo)) $cacheInfo = new CacheInfo();

        $this->db = $db;
        $this->cacheInfo = $cacheInfo;
    }

    /**
     * @inheritDoc
     */
    public function getViewedSections($userIds)
    {
        // Получаем экземпляр класса Cache
        $cache = Cache::createInstance();
        // Формируем ключ кеша для данного запроса
        $cacheKey = $this->cacheInfo->getCacheKey() . ".getViewedSections";
        // Результирующий список категорий, которые, в свою очередь, будут содержать просмотренные товары
        /** @var RelevantSection[] $relevantSections */
        $relevantSections = array();

        // Если кеш есть и он включен
        if ($this->cacheInfo->isCacheEnabled() && $cache->initCache($this->cacheInfo->getCacheLifeTime(), $cacheKey)) {
            // Достаем переменные из кеша
            $vars = $cache->getVars();
            $relevantSections = unserialize($vars["relevantSections"]);
        }
        // Если кеша нет или он неактуален (или выключен)
        elseif ($cache->startDataCache()) {
            // Собираем where условие. Первое - обязательное условие для отделения актуальных просмотренных и лайкнутых товаров
            $sqlWhere = array(
                '(pv.date_modify IS NOT null OR pv.likes_count != 0)',
                'pv.section_id = 0'
            );

            // Теперь фильтр по пользователю. Нулевой id в userIds означает, что выборка произодится по всем пользователям
            // и доп. условие в where условие не требуется
            if ($userIds[0] != 0) $sqlWhere[] = "pv.user_id IN (" . implode(',', $userIds) . ")";

            // Формируем SQL. Нас интересуют только те товары, для которых дата изменения строки не null (товар следует учитывать), либо лайкнутые товары.
            // TODO: Продумать, как исключать заказанные товары из подсчета (учитывать только лайк либо более поздние посещения страницы товара)
            // TODO: В рамках текущего запроса достаточно делать null в date_modify товара. Либо нужно сделать доп.поле с датой последнего заказа товара пользователем и фильтровать по нему
            $sql =
                "SELECT " .
                "   pv.product_id, " .
                "   el.IBLOCK_ID, " .
                "   el.IBLOCK_SECTION_ID, " .
                "   MIN(pv.date_insert) as date_insert, " .
                "   MAX(pv.date_modify) as date_modify, " .
                "   SUM(pv.likes_count) as likes_count, " .
                "   SUM(pv.views_count) as views_count " .
                "FROM {$this->productViewTableName} pv " .
                "LEFT JOIN {$this->productsTableName} el ON el.id = pv.product_id " .
                "WHERE " . implode(' AND ', $sqlWhere) . " " .
                "GROUP BY pv.product_id, el.IBLOCK_ID, el.IBLOCK_SECTION_ID ; ";

            // Выполняем запрос
            $query = $this->db->Query($sql);

            // Если запрос не выполнился
            if (!$query) {
                throw new \Exception("Не удалось получить данные о просмотренных товарах.");
            }

            // Пробегаемся по всем полученным данным
            while ($queryResult = $query->Fetch()) {
                $viewedProduct = new RelevantProduct(
                    intval($queryResult["product_id"]), intval($queryResult["IBLOCK_ID"]), intval($queryResult["IBLOCK_SECTION_ID"])
                );
                $viewedProduct->setDateFirstView($queryResult["date_insert"]);
                $viewedProduct->setDateLastView($queryResult["date_modify"]);
                $viewedProduct->setLikesCount($queryResult["likes_count"]);
                $viewedProduct->setViewsCount($queryResult["views_count"]);

                DataWrapper::mergeIntoRelevantList($relevantSections, $viewedProduct);
            }

            // Пересчитаем счетчики внутри каждой категории
            foreach ($relevantSections as $relevantSection) {
                $relevantSection->calcAllCounters();
            }

            // Теперь получаем количество просмотров самой категории, отдельно от просмотра товаров внутри этих категорий

            // Фильтр по пользователю. Нулевой id в userIds означает, что выборка произодится по всем пользователям
            // и доп. условие в where условие не требуется
            $sqlWhere = array('pv.section_id != 0');
            if ($userIds[0] != 0) $sqlWhere[] = "pv.user_id IN (" . implode(',', $userIds) . ")";

            // Формируем SQL. Нас интересуют только просмотры разделов (не товаров)
            $sql =
                "SELECT " .
                "   pv.section_id, " .
                "   MIN(pv.date_insert) as date_insert, " .
                "   MAX(pv.date_modify) as date_modify, " .
                "   SUM(pv.likes_count) as likes_count, " .
                "   SUM(pv.views_count) as views_count " .
                "FROM oip_product_view pv " .
                "WHERE " . implode(' AND ', $sqlWhere) . " "  .
                "GROUP BY pv.section_id; ";

            // Выполняем запрос
            $query = $this->db->Query($sql);

            // Если запрос не выполнился
            if (!$query) {
                throw new \Exception("Не удалось получить данные о просмотренных разделах.");
            }

            // Пробегаемся по всем полученным данным
            while ($queryResult = $query->Fetch()) {
                $sectionId = $queryResult["section_id"];
                // Если такого раздела еще нет в результирующем датасете, создадим его
                if (!isset($relevantSections[$sectionId])) {
                    $relevantSections[$sectionId] = new RelevantSection($sectionId);
                }
                $relevantSections[$sectionId]->setViewsCount($relevantSections[$sectionId]->getViewsCount() + $queryResult["views_count"]);
                // Пересчитаем "вес" раздела
                $relevantSections[$sectionId]->calcWeight();
            }

            // Записываем в кеш
            $cache->endDataCache(array("relevantSections" => serialize($relevantSections)));
        }

        return $relevantSections;
    }

    /**
     * @inheritDoc
     */
    public function getSectionProducts($userIds, $categoryIds)
    {
        // Получаем экземпляр класса Cache
        $cache = Cache::createInstance();
        // Формируем ключ кеша для данного запроса
        $cacheKey = $this->cacheInfo->getCacheKey() . ".getSectionProducts";
        // Результирующий список категорий, которые, в свою очередь, будут содержать просмотренные товары
        $categories = array();

        // Если кеш есть и он включен
        if ($this->cacheInfo->isCacheEnabled() && $cache->initCache($this->cacheInfo->getCacheLifeTime(), $cacheKey)) {
            // Достаем переменные из кеша
            $vars = $cache->getVars();
            $categories = unserialize($vars["categories"]);
        }
        // Если кеша нет или он неактуален (или выключен)
        elseif ($cache->startDataCache()) {
            // Собираем where условие. Фильтр по категориям. Нулевой id в $categoryIds означает, что выборка произодится по всем группам
            $sqlWhere = array("TRUE");
            if ($categoryIds[0] != 0) $sqlWhere[] = "COALESCE(el.IBLOCK_SECTION_ID, el.IBLOCK_ID) IN (" . implode(',', $categoryIds) . ")";

            // Собираем join условие. Фильтр по пользователю. Нулевой id в $userIds означает, что выборка произодится по всем пользователям
            $productViewJoinClause = array("el.ID = pv.product_id");
            if ($userIds[0] != 0) $productViewJoinClause[] = "pv.user_id IN (" . implode(',', $userIds) . ")";

            // Формируем SQL. Нас интересуют только те товары, для которых дата изменения строки не null (товар следует учитывать), либо лайкнутые товары.
            // TODO: Продумать, как исключать заказанные товары из подсчета (учитывать только лайк либо более поздние посещения страницы товара)
            // TODO: В рамках текущего запроса достаточно делать null в date_modify товара. Либо нужно сделать доп.поле с датой последнего заказа товара пользователем и фильтровать по нему
            $sql =
                "SELECT " .
                "   el.ID as product_id, " .
                "   el.IBLOCK_ID, " .
                "   el.IBLOCK_SECTION_ID,  " .
                "   MIN(pv.date_insert) as date_insert, " .
                "   MAX(pv.date_modify) as date_modify, " .
                "   SUM(pv.likes_count) as likes_count, " .
                "   SUM(CASE WHEN (pv.date_modify IS NOT null OR pv.likes_count != 0) THEN pv.views_count ELSE 0 END) as views_count " .
                "FROM {$this->productsTableName} el " .
                "LEFT JOIN {$this->productViewTableName} pv ON " . implode(' AND ', $productViewJoinClause) . " " .
                "WHERE " . implode(' AND ', $sqlWhere) . " " .
                "GROUP BY el.id, el.IBLOCK_ID, el.IBLOCK_SECTION_ID ; ";

            // Выполняем запрос
            $query = $this->db->Query($sql);

            // Если запрос не выполнился
            if (!$query) {
                throw new \Exception("Не удалось получить данные о просмотренных товарах.");
            }

            // Пробегаемся по всем полученным данным
            while ($queryResult = $query->Fetch()) {
                $viewedProduct = new RelevantProduct(
                    intval($queryResult["product_id"]), intval($queryResult["IBLOCK_ID"]), intval($queryResult["IBLOCK_SECTION_ID"])
                );
                $viewedProduct->setDateFirstView($queryResult["date_insert"]);
                $viewedProduct->setDateLastView($queryResult["date_modify"]);
                $viewedProduct->setLikesCount($queryResult["likes_count"]);
                $viewedProduct->setViewsCount($queryResult["views_count"]);

                DataWrapper::mergeIntoRelevantList($categories, $viewedProduct);
            }

            // Пересчитаем счетчики внутри каждой категории
            /** @var RelevantSection $category*/
            foreach ($categories as $category) {
                $category->calcAllCounters();
            }
            // Записываем в кеш
            $cache->endDataCache(array("categories" => serialize($categories)));
        }

        return $categories;
    }

    /**
     * @inheritDoc
     */
    public function getViewedProducts($userIds, $productIds)
    {
        // Получаем экземпляр класса Cache
        $cache = Cache::createInstance();
        // Формируем ключ кеша для данного запроса
        $cacheKey = $this->cacheInfo->getCacheKey() . ".getViewedProducts";
        // Результирующий список категорий, которые, в свою очередь, будут содержать просмотренные товары
        $viewedProducts = array();

        // Если кеш есть и он включен
        if ($this->cacheInfo->isCacheEnabled() && $cache->initCache($this->cacheInfo->getCacheLifeTime(), $cacheKey)) {
            // Достаем переменные из кеша
            $vars = $cache->getVars();
            $viewedProducts = unserialize($vars["viewedProducts"]);
        }
        // Если кеша нет или он неактуален (или выключен)
        elseif ($cache->startDataCache()) {
            // Собираем where условие. Фильтр по категориям. Нулевой id в $categoryIds означает, что выборка произодится по всем группам
            $sqlWhere = array("TRUE");
            if ($productIds[0] != 0) $sqlWhere[] = "el.ID IN (" . implode(',', $productIds) . ")";

            // Собираем join условие. Фильтр по пользователю. Нулевой id в $userIds означает, что выборка произодится по всем пользователям
            $productViewJoinClause = array("el.ID = pv.product_id");
            if ($userIds[0] != 0) $productViewJoinClause[] = "pv.user_id IN (" . implode(',', $userIds) . ")";

            // Формируем SQL. Нас интересуют только те товары, для которых дата изменения строки не null (товар следует учитывать), либо лайкнутые товары.
            // TODO: Продумать, как исключать заказанные товары из подсчета (учитывать только лайк либо более поздние посещения страницы товара)
            // TODO: В рамках текущего запроса достаточно делать null в date_modify товара. Либо нужно сделать доп.поле с датой последнего заказа товара пользователем и фильтровать по нему
            $sql =
                "SELECT " .
                "   pv.user_id, " .
                "   el.ID as product_id, " .
                "   el.IBLOCK_ID, " .
                "   el.IBLOCK_SECTION_ID,  " .
                "   MIN(pv.date_insert) as date_insert, " .
                "   MAX(pv.date_modify) as date_modify, " .
                "   SUM(pv.likes_count != 0) as likes_count, " .
                "   SUM(CASE WHEN (pv.date_modify IS NOT null OR pv.likes_count != 0) THEN pv.views_count ELSE 0 END) as views_count " .
                "FROM {$this->productsTableName} el " .
                "JOIN {$this->productViewTableName} pv ON " . implode(' AND ', $productViewJoinClause) . " " .
                "WHERE " . implode(' AND ', $sqlWhere) . " " .
                "GROUP BY el.id, el.IBLOCK_ID, el.IBLOCK_SECTION_ID, pv.user_id ; ";

            // Выполняем запрос
            $query = $this->db->Query($sql);

            // Если запрос не выполнился
            if (!$query) {
                throw new \Exception("Не удалось получить данные о просмотренных товарах.");
            }

            // Пробегаемся по всем полученным данным
            while ($queryResult = $query->Fetch()) {
                $viewedProduct = new RelevantProduct(
                    intval($queryResult["product_id"]), intval($queryResult["IBLOCK_ID"]), intval($queryResult["IBLOCK_SECTION_ID"])
                );
                $viewedProduct->setDateFirstView($queryResult["date_insert"]);
                $viewedProduct->setDateLastView($queryResult["date_modify"]);
                $viewedProduct->setLikesCount($queryResult["likes_count"]);
                $viewedProduct->setViewsCount($queryResult["views_count"]);

                $viewedProducts[] = $viewedProduct;
                //DataWrapper::mergeIntoRelevantList($categories, $viewedProduct);
            }

            // Пересчитаем счетчики внутри каждой категории
            /** @var RelevantSection $category*/
//            foreach ($categories as $category) {
//                $category->calcAllCounters();
//            }
            // Записываем в кеш
            $cache->endDataCache(array("viewedProducts" => serialize($viewedProducts)));
        }

        return $viewedProducts;
    }

    // TODO: Можно сделать дополнительную функцию, которая вернет не плоский массив RelevantProduct, а обратится к
    // TODO: getViewedProducts и в foreach замерджит их в массив RelevantSection (DataWrapper::mergeIntoRelevantList)

    /**
     * @inheritDoc
     */
    public function addProductView($userId, $productId)
    {
        // Проверим, достаточно ли времени прошло с момента прошлого обновления просмотров данного товара
        $sql =
            "SELECT 1 " .
            "FROM {$this->productViewTableName} pv " .
            "WHERE pv.user_id = '{$userId}' AND pv.product_id = '{$productId}' " .
            "AND NOW() < pv.date_modify + INTERVAL " . Configuration::ADD_PRODUCT_VIEW_INTERVAL . " MINUTE ; ";

        // Выполняем запрос
        $query = $this->db->Query($sql);

        // Если не нашлось "свежей" записи о просмотре данного товара данным пользователем - запишем просмотр в таблицу
        if (!$query->SelectedRowsCount()) {
            $sql =
                "INSERT INTO {$this->productViewTableName} (user_id, product_id, views_count) " .
                "VALUES ('{$userId}', '{$productId}', '1') " .
                "ON DUPLICATE KEY UPDATE date_modify = NOW(), views_count = views_count + 1; ";

            // Выполняем запрос
            $query = $this->db->Query($sql);
            // Если запрос не выполнился
            if (!$query) throw new \Exception("Не удалось выполнить запрос на обновление данных о просмотре товара.");
            // Если запись не была добавлена
            if ($query->AffectedRowsCount() == 0) throw new \Exception("Не удалось обновить данные о просмотре товара.");
        }
    }

    /**
     * @inheritDoc
     */
    public function addProductLike($userId, $productId)
    {
        $sql =
            "INSERT INTO {$this->productViewTableName} (user_id, product_id, likes_count) " .
            "VALUES ('{$userId}', '{$productId}', '1') " .
            "ON DUPLICATE KEY UPDATE date_modify = NOW(), likes_count = '1'; ";

        // Выполняем запрос
        $query = $this->db->Query($sql);
        // Если запрос не выполнился
        if (!$query) throw new \Exception("Не удалось выполнить обновление данных о понравившемся товаре.");
        // Если запись не была добавлена
        if ($query->AffectedRowsCount() == 0) throw new \Exception("Не удалось обновить данные о понравившемся товаре.");
    }

    /**
     * @inheritDoc
     */
    public function deleteProductLike($userId, $productId)
    {
        $sql =
            "UPDATE {$this->productViewTableName} " .
            "SET likes_count = 0 " .
            "WHERE user_id = '{$userId}' AND product_id = '{$productId}' ";

        // Выполняем запрос
        $query = $this->db->Query($sql);
        // Если запрос не выполнился
        if (!$query) throw new \Exception("Не удалось выполнить обновление данных о понравившемся товаре.");
    }

    /**
     * @inheritDoc
     */
    public function addSectionView($userId, $sectionId)
    {
        // Проверим, достаточно ли времени прошло с момента прошлого обновления просмотров данного товара
        $sql =
            "SELECT 1 " .
            "FROM {$this->productViewTableName} pv " .
            "WHERE pv.user_id = '{$userId}' AND pv.section_id = '{$sectionId}' " .
            "AND NOW() < pv.date_modify + INTERVAL " . Configuration::ADD_SECTION_VIEW_INTERVAL . " MINUTE ; ";

        // Выполняем запрос
        $query = $this->db->Query($sql);

        // Если не нашлось "свежей" записи о просмотре данного раздела данным пользователем - запишем просмотр в таблицу
        if (!$query->SelectedRowsCount()) {
            $sql =
                "INSERT INTO {$this->productViewTableName} (user_id, section_id, product_id, views_count) " .
                "VALUES ('{$userId}', '{$sectionId}', '0', '1') " .
                "ON DUPLICATE KEY UPDATE date_modify = NOW(), views_count = views_count + 1; ";

            // Выполняем запрос
            $query = $this->db->Query($sql);
            // Если запрос не выполнился
            if (!$query) throw new \Exception("Не удалось выполнить запрос на обновление данных о просмотре раздела.");
            // Если запись не была добавлена
            if ($query->AffectedRowsCount() == 0) throw new \Exception("Не удалось обновить данные о просмотре раздела.");
        }
    }

    /**
     * @inheritDoc
     */
    public function addSectionLike($userId, $sectionId)
    {
        $sql =
            "INSERT INTO {$this->productViewTableName} (user_id, section_id, product_id, likes_count) " .
            "VALUES ('{$userId}', '{$sectionId}', 0, 1) " .
            "ON DUPLICATE KEY UPDATE date_modify = NOW(), likes_count = 1; ";

        // Выполняем запрос
        $query = $this->db->Query($sql);
        // Если запрос не выполнился
        if (!$query) throw new \Exception("Не удалось выполнить обновление данных о понравившемся разделе.");
        // Если запись не была добавлена
        if ($query->AffectedRowsCount() == 0) throw new \Exception("Не удалось обновить данные о понравившемся разделе.");
    }

    /**
     * @inheritDoc
     */
    public function deleteSectionLike($userId, $sectionId)
    {
        $sql =
            "UPDATE {$this->productViewTableName} " .
            "SET likes_count = 0 " .
            "WHERE user_id = '{$userId}' AND section_id = '{$sectionId}' ";

        // Выполняем запрос
        $query = $this->db->Query($sql);
        // Если запрос не выполнился
        if (!$query) throw new \Exception("Не удалось выполнить обновление данных о понравившемся разделе.");
    }

    /**
     * @inheritDoc
     */
    public function getMostViewedSections() {
        // Получаем экземпляр класса Cache
        $cache = Cache::createInstance();
        // Формируем ключ кеша для данного запроса
        $cacheKey = $this->cacheInfo->getCacheKey() . ".getMostViewedSections";
        // Результирующий список категорий, которые, в свою очередь, будут содержать просмотренные товары
        /** @var RelevantSection[] $viewedSections */
        $viewedSections = array();

        // Если кеш есть и он включен
        if ($this->cacheInfo->isCacheEnabled() && $cache->initCache($this->cacheInfo->getCacheLifeTime(), $cacheKey)) {
            // Достаем переменные из кеша
            $vars = $cache->getVars();
            $viewedCategories = unserialize($vars["viewedSections"]);
        }
        // Если кеша нет или он неактуален (или выключен)
        elseif ($cache->startDataCache()) {
            // Собираем where условие.
            // Первое - обязательное условие для отделения актуальных просмотренных и лайкнутых товаров
            // Второе - отсекает просмотры разделов (без захода в деталку товара)
            $sqlWhere = array(
                '(pv.date_modify IS NOT null OR pv.likes_count != 0)',
                'section_id = 0'
            );

            // Формируем SQL. Нас интересуют только те товары, для которых дата изменения строки не null (товар следует учитывать), либо лайкнутые товары.
            $sql =
                "SELECT " .
                "   el.IBLOCK_ID,  " .
                "   el.IBLOCK_SECTION_ID,  " .
                "   COALESCE(el.IBLOCK_SECTION_ID, IBLOCK_ID) as parent_section_id,  " .
                "   MIN(pv.date_insert) as date_insert, " .
                "   MAX(pv.date_modify) as date_modify, " .
                "   SUM(pv.likes_count) as likes_count, " .
                "   SUM(pv.views_count) as views_count " .
                "FROM {$this->productViewTableName} pv " .
                "LEFT JOIN {$this->productsTableName} el ON el.ID = pv.product_id " .
                "WHERE " . implode(' AND ', $sqlWhere) . " " .
                "GROUP BY el.IBLOCK_ID, el.IBLOCK_SECTION_ID; ";

            // Выполняем запрос
            $query = $this->db->Query($sql);

            // Если запрос не выполнился
            if (!$query) {
                throw new \Exception("Не удалось получить данные о просмотренных товарах.");
            }

            // Пробегаемся по всем полученным данным
            while ($queryResult = $query->Fetch()) {
                $viewedSection = new RelevantSection(intval($queryResult["parent_section_id"]));
                $viewedSection->setLikesCount($queryResult["likes_count"]);
                $viewedSection->setViewsCount($queryResult["views_count"]);
                $viewedSections[$viewedSection->getId()] = $viewedSection;
            }

            // Теперь запрашиваем данные о просмотре только разделов (списка товаров разделе, не заходя в сам товар)
            // Формируем SQL. Нас интересуют только просмотры разделов (не товаров)
            $sql =
                "SELECT " .
                "   pv.section_id, " .
                "   MIN(pv.date_insert) as date_insert, " .
                "   MAX(pv.date_modify) as date_modify, " .
                "   SUM(pv.likes_count) as likes_count, " .
                "   SUM(pv.views_count) as views_count " .
                "FROM oip_product_view pv " .
                "WHERE pv.section_id != 0 " .
                "GROUP BY pv.section_id; ";

            // Выполняем запрос
            $query = $this->db->Query($sql);

            // Если запрос не выполнился
            if (!$query) {
                throw new \Exception("Не удалось получить данные о просмотренных товарах.");
            }

            // Пробегаемся по всем полученным данным
            while ($queryResult = $query->Fetch()) {
                $sectionId = $queryResult["section_id"];
                // Если такого раздела еще нет в результирующем датасете, создадим его
                if (!isset($viewedSections[$sectionId])) {
                    $viewedSections[$sectionId] = new RelevantSection($sectionId);
                }
                $viewedSections[$sectionId]->setViewsCount($viewedSections[$sectionId]->getViewsCount() + $queryResult["views_count"]);
            }

            // Записываем в кеш
            $cache->endDataCache(array("viewedSections" => serialize($viewedSections)));
        }

        return $viewedSections;
    }

    /**
     * @inheritDoc
     */
    public function getMostViewedProducts() {
        // Получаем экземпляр класса Cache
        $cache = Cache::createInstance();
        // Формируем ключ кеша для данного запроса
        $cacheKey = $this->cacheInfo->getCacheKey() . ".getMostViewedCategories";
        // Результирующий список категорий, которые, в своб очередь, будут содержать просмотренные товары
        $viewedProducts = array();

        // Если кеш есть и он включен
        if ($this->cacheInfo->isCacheEnabled() && $cache->initCache($this->cacheInfo->getCacheLifeTime(), $cacheKey)) {
            // Достаем переменные из кеша
            $vars = $cache->getVars();
            $viewedProducts = unserialize($vars["viewedProducts"]);
        }
        // Если кеша нет или он неактуален (или выключен)
        elseif ($cache->startDataCache()) {
            // Собираем where условие. Первое - обязательное условие для отделения актуальных просмотренных и лайкнутых товаров
            $sqlWhere = array(
                '(pv.date_modify IS NOT null OR pv.likes_count != 0)',
                'pv.section_id = 0'
        );

            // Формируем SQL. Нас интересуют только те товары, для которых дата изменения строки не null (товар следует учитывать), либо лайкнутые товары.
            $sql =
                "SELECT " .
                "   el.ID as product_id,  " .
                "   el.IBLOCK_ID,   " .
                "   el.IBLOCK_SECTION_ID,  " .
                "   MIN(pv.date_insert) as date_insert, " .
                "   MAX(pv.date_modify) as date_modify, " .
                "   SUM(pv.likes_count) as likes_count, " .
                "   SUM(pv.views_count) as views_count " .
                "FROM {$this->productViewTableName} pv " .
                "LEFT JOIN {$this->productsTableName} el ON el.ID = pv.product_id " .
                "WHERE " . implode(' AND ', $sqlWhere) . " " .
                "GROUP BY el.ID, el.IBLOCK_ID, el.IBLOCK_SECTION_ID; ";

            // Выполняем запрос
            $query = $this->db->Query($sql);

            // Если запрос не выполнился
            if (!$query) {
                throw new \Exception("Не удалось получить данные о просмотренных товарах.");
            }

            // Пробегаемся по всем полученным данным
            while ($queryResult = $query->Fetch()) {
                $viewedProduct = new RelevantProduct($queryResult["product_id"], $queryResult["IBLOCK_ID"], $queryResult["IBLOCK_SECTION_ID"]);
                $viewedProduct->setLikesCount($queryResult["likes_count"]);
                $viewedProduct->setViewsCount($queryResult["views_count"]);
                $viewedProducts[] = $viewedProduct;
            }
            // Записываем в кеш
            $cache->endDataCache(array("viewedProducts" => serialize($viewedProducts)));
        }

        return $viewedProducts;
    }

    /**
     * @inheritDoc
     */
    public function getNewProductCategories() {
        // Получаем экземпляр класса Cache
        $cache = Cache::createInstance();
        // Формируем ключ кеша для данного запроса
        $cacheKey = $this->cacheInfo->getCacheKey() . ".getNewProductCategories";
        // Результирующий список категорий, которые, в свою очередь, будут содержать новые товары
        $newProductCategories = array();

        // Если кеш есть и он включен
        if ($this->cacheInfo->isCacheEnabled() && $cache->initCache($this->cacheInfo->getCacheLifeTime(), $cacheKey)) {
            // Достаем переменные из кеша
            $vars = $cache->getVars();
            $newProductCategories = unserialize($vars["newProductCategories"]);
        }
        // Если кеша нет или он неактуален (или выключен)
        elseif ($cache->startDataCache()) {
            // Собираем where условие. Первое - обязательное условие для выборки только "свежих" товаров
            $sqlWhere = array('TIMESTAMPDIFF(MINUTE, el.date_create, NOW()) <= ' . Configuration::NEW_PRODUCT_LIFETIME);

            // Формируем SQL.
            $sql =
                "SELECT " .
                "   TIMESTAMPDIFF(MINUTE, el.date_create, NOW()) as time_after_adding, " .
                "   el.ID as product_id,  " .
                "   el.IBLOCK_ID, " .
                "   el.IBLOCK_SECTION_ID, " .
                "   SUM(pv.likes_count) as likes_count, " .
                "   SUM(CASE WHEN (pv.date_modify IS NOT null OR pv.likes_count != 0) THEN pv.views_count ELSE 0 END) as views_count, " .
                "   MIN(pv.date_insert) as date_insert, " .
                "   MAX(pv.date_modify) as date_modify " .
                "FROM {$this->productsTableName} el " .
                "LEFT JOIN {$this->productViewTableName} pv ON pv.product_id = el.ID " .
                "WHERE " . implode(' AND ', $sqlWhere) . " " .
                "GROUP BY time_after_adding, el.id, el.IBLOCK_ID, el.IBLOCK_SECTION_ID, el.date_create; ";

            // Выполняем запрос
            $query = $this->db->Query($sql);

            // Если запрос не выполнился
            if (!$query) {
                throw new \Exception("Не удалось получить данные о просмотренных товарах.");
            }

            // Пробегаемся по всем полученным данным
            while ($queryResult = $query->Fetch()) {
                $relevantProduct = new RelevantProduct(
                    intval($queryResult["product_id"]), intval($queryResult["IBLOCK_ID"]), intval($queryResult["IBLOCK_SECTION_ID"])
                );
                $relevantProduct->setDateFirstView($queryResult["date_insert"]);
                $relevantProduct->setDateLastView($queryResult["date_modify"]);
                $relevantProduct->setLikesCount($queryResult["likes_count"]);
                $relevantProduct->setViewsCount($queryResult["views_count"]);

                // Динамическое поле с названием категории, нужно только на случай создания новой категории при мердже
                DataWrapper::mergeIntoRelevantList($newProductCategories, $relevantProduct);
            }
            // Записываем в кеш
            $cache->endDataCache(array("newProductCategories" => serialize($newProductCategories)));
        }

        return $newProductCategories;
    }

    /**
     * @inheritDoc
     */
    public function getFreeGuestId() {
        // Добавляем запись в таблицу просмотров товара
        $sql =
            "INSERT INTO {$this->productViewTableName} (user_id) " .
            "SELECT COALESCE(CASE WHEN MIN(pv.user_id) - 1 >= 0 THEN -1 ELSE MIN(pv.user_id) - 1 END, -1) " .
            "FROM {$this->productViewTableName} pv ";

        // Выполняем запрос
        $query = $this->db->Query($sql);
        // Если запрос не выполнился
        if (!$query) throw new \Exception("Не удалось выполнить запрос на получение идентификатора нового гостевого пользователя.");
        // Если запись не была добавлена
        if ($query->AffectedRowsCount() == 0) throw new \Exception("Не удалось добавить запись с идентификатором нового гостевого пользователя.");

        // Получаем идентификатор только что созданного нового идентификатора гостевого пользователя
        global $DB;
        $newGuestUserRowId = $DB->LastID();
        // Узнаем, какой ID записался
        $sql =
            "SELECT user_id " .
            "FROM {$this->productViewTableName} " .
            "WHERE id = {$newGuestUserRowId} ";

        // Выполняем запрос
        $query = $this->db->Query($sql);
        // Если запрос не выполнился
        if (!$query) throw new \Exception("Не удалось выполнить запрос на получение данных об идентификаторе нового гостевого пользователя.");
        // Если запись не была получена
        if ($query->SelectedRowsCount() == 0) throw new \Exception("Запись с идентификатором нового гостевого пользователя не была получена.");

        // Возвращаем полученный user_id
        $queryResult = $query->Fetch();
        return $queryResult["user_id"];
    }

    /**
     * @inheritdoc
     */
    public function getUserLikes(int $userId): int {
        return (int)$this->db->Query("SELECT COUNT(1) as cnt FROM {$this->productViewTableName} WHERE user_id = $userId"
            ."  AND likes_count > 0")->Fetch()["cnt"];
    }
}
