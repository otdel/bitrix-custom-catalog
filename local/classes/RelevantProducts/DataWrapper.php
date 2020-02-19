<?php

namespace Oip\RelevantProducts;

use Exception;

class DataWrapper
{
    const GLOBAL_PRODUCT_LIKE_ACTION_NAME = "oipProductLikeAction";
    const GLOBAL_PRODUCT_LIKE_ACTION_ADD = "oipProductLikeAdd";
    const GLOBAL_PRODUCT_LIKE_ACTION_REMOVE = "oipProductLikeRemove";
    const GLOBAL_PRODUCT_LIKE_PRODUCT_ID = "oipProductLikeProductId";

    /** @var \Oip\RelevantProducts\DataSourceInterface */
    private $ds;

    /**
     * DataWrapper constructor.
     * @param DataSourceInterface $dataSource Реализация интерфейса \Oip\RelevantProducts\DataSourceInterface
     */
    public function __construct(DataSourceInterface $dataSource)
    {
        $this->ds = $dataSource;
    }

    /**
     * @see DataSourceInterface::getViewedSections()
     */
    public function getViewedSections($userIds) {
        $userIds = self::prepareIdsArgument($userIds);
        $viewedCategories = $this->ds->getViewedSections($userIds);

        // Оставляем только категории с id > 0
        $viewedCategories = array_filter($viewedCategories, function($obj){
            /** @var RelevantSection $obj */
            return $obj->getId() > 0;
        });

        // Сортируем по весу, в порядке убывания. Название статической функции сравнения следует передавать с доп. указанием класса
        usort($viewedCategories, array('Oip\RelevantProducts\CompareFunctions','compareCategoriesByWeight'));

        return $viewedCategories;
    }

    /**
     * @see DataSourceInterface::getViewedProducts()
     */
    public function getViewedProducts($userIds, $productIds) {
        return $this->ds->getViewedProducts(self::prepareIdsArgument($userIds), self::prepareIdsArgument($productIds));
    }

    /**
     * @see DataSourceInterface::getSectionProducts()
     */
    public function getSectionProducts($userIds, $sectionIds) {
        return $this->ds->getSectionProducts(self::prepareIdsArgument($userIds), self::prepareIdsArgument($sectionIds));
    }

    /**
     * @see DataSourceInterface::addProductView()
     */
    public function addProductView($userId, $productId){
        return $this->ds->addProductView($userId, $productId);
    }

    /**
     * @see DataSourceInterface::addProductLike()
     */
    public function addProductLike($userId, $productId){
        return $this->ds->addProductLike($userId, $productId);
    }

    /**
     * @param int $userId
     * @param int $productId
     * @throws Exception;
     */
    public function removeProductLike($userId, $productId): void {
        $this->ds->deleteProductLike($userId, $productId);
    }

    /**
     * @see DataSourceInterface::addSectionView()
     */
    public function addSectionView($userId, $sectionId){
        return $this->ds->addSectionView($userId, $sectionId);
    }

    /**
     * @see DataSourceInterface::addSectionLike()
     */
    public function addSectionLike($userId, $sectionId){
        return $this->ds->addSectionLike($userId, $sectionId);
    }

    /**
     * @see DataSourceInterface::deleteSectionLike()
     */
    public function deleteSectionLike($userId, $sectionId){
        return $this->ds->deleteSectionLike($userId, $sectionId);
    }

    /**
     * Добавляет товар в общий список релевантных категорий.
     * Если товар из новой категории (которой еще нет в списке), будет создана категория и
     * в нее будет добавлен этот самый товар. Если категория существует - просто добавит товар в существующую категорию.
     *
     * @param RelevantSection[] $relevantSections Список уже существующих категорий товаров
     * @param RelevantProduct $relevantProduct Товар, который необходимо добавить
     */
    public static function mergeIntoRelevantList(&$relevantSections, $relevantProduct) {
        // Проверяем, существует ли категория с искомым идентификатором
        $currentSection = null;
        foreach ($relevantSections as $relevantSection) {
            if ($relevantSection->getId() == $relevantProduct->getSectionId()) {
                $currentSection = $relevantSection;
                break;
            }
        }

        // Если такой категории еще не существует - создаем и добавляем в нее товар
        if ($currentSection === null) {
            $currentSection = new RelevantSection($relevantProduct->getIBlockSectionId());
            $currentSection->addRelevantProduct($relevantProduct);
            $relevantSections[$relevantProduct->getIBlockSectionId()] = $currentSection;
        }
        // Иначе - просто добавляем товар
        else {
            $currentSection->addRelevantProduct($relevantProduct);
        }
    }

    /**
     * Получение списка наиболее просматриваемых категорий среди всех пользователей
     *
     * @return RelevantSection[]|null
     * @throws \Exception
     */
    public function getMostViewedCategories() {
        // Получаем список просмотренных пользователем[-ями] товаров
        $mostViewedCategories = $this->ds->getMostViewedSections();
        // Оставляем только категории с id > 0
        $mostViewedCategories = array_filter($mostViewedCategories, function($obj){
            /** @var RelevantSection $obj */
            return $obj->getId() > 0;
        });
        // Если ни в одной категории нет просмотров
        if (count($mostViewedCategories) == 0) return null;
        // Отсортируем по количеству просмотров
        usort($mostViewedCategories, array('Oip\RelevantProducts\CompareFunctions', 'compareCategoriesByViews'));
        //
        return $mostViewedCategories;
    }

    /**
     * Получение списка наиболее залайканных категорий среди всех пользователей
     *
     * @return RelevantSection[]|null
     * @throws \Exception
     */
    public function getMostLikedCategories() {
        // Получаем список просмотренных пользователем[-ями] товаров
        $mostLikedCategories = $this->ds->getMostViewedSections();
        // Оставляем только категории с id > 0
        $mostLikedCategories = array_filter($mostLikedCategories, function($obj){
            /** @var RelevantSection $obj */
            return $obj->getId() > 0;
        });
        // Если ни в одной категории нет просмотров
        if (count($mostLikedCategories) == 0) return null;
        // Отсортируем по количеству лайков
        usort($mostLikedCategories, array('Oip\RelevantProducts\CompareFunctions', 'compareCategoriesByLikes'));
        //
        return $mostLikedCategories;
    }

    /**
     * Получение списка наиболее просматриваемых товаров среди всех пользователей
     *
     * @return RelevantProduct[]|null $mostViewedProducts
     * @throws \Exception
     */
    public function getMostViewedProducts() {
        // Получаем список просмотренных пользователем[-ями] товаров
        $mostViewedProducts = $this->ds->getMostViewedProducts();
        // Если ни в одной категории нет просмотров
        if (count($mostViewedProducts) == 0) return null;
        // Отсортируем по количеству просмотров
        usort($mostViewedProducts, array('Oip\RelevantProducts\CompareFunctions', 'compareProductsByViews'));
        //
        return $mostViewedProducts;
    }

    /**
     * Получение списка наиболее залайканных товаров среди всех пользователей
     *
     * @return RelevantProduct[]|null $mostLikedProducts
     * @throws \Exception
     */
    public function getMostLikedProducts() {
        // Получаем список просмотренных пользователем[-ями] товаров
        $mostLikedProducts = $this->ds->getMostViewedProducts();
        // Если ни в одной категории нет лайков
        if (count($mostLikedProducts) == 0) return null;
        // Отсортируем по количеству лайков
        usort($mostLikedProducts, array('Oip\RelevantProducts\CompareFunctions', 'compareProductsByLikes'));
        //
        return $mostLikedProducts;
    }

    /**
     * Получение списка категорий с новыми товарами в них
     *
     * @return RelevantSection[]|null $relevantCategories Список категорий (с новыми товарами внутри них)
     * @throws \Exception
     */
    public function getNewProductCategories() {
        // Получаем список просмотренных пользователем[-ями] товаров
        $newProductCategories = $this->ds->getNewProductCategories();
        // Оставляем только категории с id > 0
        $newProductCategories = array_filter($newProductCategories, function($obj){
            /** @var RelevantSection $obj */
            return $obj->getId() > 0;
        });
        // Если ни в одной категории нет новых товаров
        if (count($newProductCategories) == 0) return null;
        // Отсортируем по количеству лайков
        usort($newProductCategories, array('Oip\RelevantProducts\CompareFunctions', 'compareCategoriesByWeight'));
        //
        return $newProductCategories;
    }

    /**
     * Получение списка популярных у пользователя категорий (без товаров внутри)
     *
     * @param int|int[] $userIds
     * @return RelevantSection[]|null $relevantCategories Список категорий (с новыми товарами внутри них)
     * @throws \Exception
     */
    public function getMostViewedCategoriesList($userIds) {
        // Получаем список просмотренных пользователем[-ями] товаров
        $newProductCategories = $this->ds->getNewProductCategories();
        // Оставляем только категории с id > 0
        $newProductCategories = array_filter($newProductCategories, function($obj){
            /** @var RelevantSection $obj */
            return $obj->getId() > 0;
        });
        // Если ни в одной категории нет новых товаров
        if (count($newProductCategories) == 0) return null;
        // Отсортируем по количеству лайков
        usort($newProductCategories, array('Oip\RelevantProducts\CompareFunctions', 'compareCategoriesByWeight'));
        //
        return $newProductCategories;
    }

    /**
     * Подготовка входного аргумента со списком идентификаторов.
     *
     * @param int|int[]
     * @return int[]
     * @throws \Exception
     */
    private static function prepareIdsArgument($userIds) {
        // Если пришло просто число, а не массив айдишников - сделаем из него массив для дальнейшей работы
        if (!is_array($userIds) && is_numeric($userIds)) {
            $userIds = array($userIds);
        }

        // Удостоверимся, что нам пришел именно массив id, состоящий только из чисел
        if (count($userIds) == 0 || !($userIds === array_filter($userIds,'is_numeric'))) {
            throw new \Exception("Ошибка входных данных. Идентификаторы пользователей некорректны.");
        }

        return $userIds;
    }

    /**
     * Получение свободного идентфиикатора для нового гостевого пользователя
     */
    public function getFreeGuestId() {
        return $this->ds->getFreeGuestId();
    }

    /**
     * @param int $userId
     * @return int
     */
    public function getUserLikes(int $userId) {
        return $this->ds->getUserLikes($userId);
    }

    /**
     * @param int $productId
     * @param int $userId
     * @return bool
     */
    public function isProductLikedByUser(int $productId, int $userId): bool {
        return $this->ds->isProductLikedByUser($productId, $userId);
    }

    /**
     * @param int $productId
     * @return int
     */
    public function getProductLikes(int $productId): int {
        return $this->ds->getProductLikes($productId);
    }

    /**
     * @param int $productId
     * @return int
     */
    public function getProductViews(int $productId): int {
        return $this->ds->getProductViews($productId);
    }
    /**
     * @see DataSourceInterface::getSectionLikesCount()
     */
    public function getSectionLikesCount($sectionId) {
        return $this->ds->getSectionLikesCount($sectionId);
    }

    /**
     * @see DataSourceInterface::getSubsectionsId()
     */
    public function getSubsectionsId($sectionId) {
        return $this->ds->getSubsectionsId($sectionId);
    }

    /**
     * @param int $sectionId
     * @param int $userId
     * @return bool
     */
    public function isSectionLikedByUser(int $sectionId, int $userId): bool {
        return $this->ds->isSectionLikedByUser($sectionId, $userId);
    }
}
