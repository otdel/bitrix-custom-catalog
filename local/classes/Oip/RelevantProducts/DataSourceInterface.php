<?php
namespace Oip\RelevantProducts;

interface DataSourceInterface {

    /**
     * @param int[] $userIds Идентификаторы пользователей
     * @return RelevantProduct[]
     * @throws \Exception
     */
    public function getViewedSections($userIds);

    /**
     * @param int $userId Идентификатор пользователя
     * @param int $productId Идентификатор товара
     * @throws \Exception;
     */
    public function addProductView($userId, $productId);

    /**
     * @param int $userId Идентификатор пользователя
     * @param int $productId Идентификатор товара
     * @throws \Exception;
     */
    public function addProductLike($userId, $productId);

    /**
     * @param int $userId Идентификатор пользователя
     * @param int $productId Идентификатор товара
     * @throws \Exception;
     */
    public function deleteProductLike($userId, $productId);

    /**
     * @param int $userId Идентификатор пользователя
     * @param int $sectionId Идентификатор раздела
     * @throws \Exception;
     */
    public function addSectionView($userId, $sectionId);

    /**
     * @param int $userId Идентификатор пользователя
     * @param int $productId Идентификатор раздела
     * @throws \Exception;
     */
    public function addSectionLike($userId, $productId);

    /**
     * @param int $userId Идентификатор пользователя
     * @param int $productId Идентификатор раздела
     * @throws \Exception;
     */
    public function deleteSectionLike($userId, $productId);

    /**
     * Получение товаров из категории[-ий] с получением информации о просмотрах/лайках
     *
     * @param int|int[] $userIds Идентификатор[-ы] пользователя[-ей]
     * @param int|int[] $sectionIds Идентификаторы[-ы] категории[-й]
     * @return RelevantSection[]|null
     * @throws \Exception;
     */
    public function getSectionProducts($userIds, $sectionIds);

    /**
     * Получение массива просмотренных (либо лайкнутых) пользователем товаров
     *
     * @param int[] $userIds Идентификаторы пользователей
     * @param int[] $productIds Идентификаторы товаров
     * @return RelevantProduct[]
     * @throws \Exception
     */
    public function getViewedProducts($userIds, $productIds);

    /**
     * Получение самых просматриваемых разделов
     *
     * @return RelevantProduct[]
     * @throws \Exception
     */
    public function getMostViewedSections();

    /**
     * @return RelevantProduct[]
     * @throws \Exception
     */
    public function getMostViewedProducts();

    /**
     * @return RelevantSection[]
     * @throws \Exception
     */
    public function getNewProductCategories();


}
