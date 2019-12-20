<?php

namespace Oip\SocialStore\Cart\Repository;

use Bitrix\Main;
use Oip\SocialStore\Product\Entity;
use Oip\SocialStore\Product\Factory\ProductCollection as ProductsFactory;

use Oip\SocialStore\Product\Exception\InvalidObjectType;
use Oip\SocialStore\Product\Exception\NonUniqueIdCreating;

use Oip\SocialStore\Cart\Exception\ItemExists;
use Oip\SocialStore\Cart\Exception\ItemDoesntExist;
use Oip\SocialStore\Cart\Exception\ItemsDontExist;
use Oip\SocialStore\Cart\Exception\ItemDuplicates;

class DBRepository implements RepositoryInterface
{
    private $cartsTableName = "oip_carts";
    private $elementsTableName = "b_iblock_element";
    private $filesTableName = "b_file";

    /** @var Main\DB\Connection $db */
    private $db;

    /** @var Main\DB\Connection $connection */
    public function __construct(Main\DB\Connection $connection)
    {
        $this->db = $connection;
    }

    /**
     * @inheritdoc
     *
     * @throws Main\Db\SqlQueryException
     * @throws NonUniqueIdCreating
     * @throws InvalidObjectType
     */
    public function getByUserId($userId): Entity\ProductCollection
    {

        $products = [];
        $sql = self::getByUserIdSql($userId);

        foreach($this->db->query($sql)->fetchAll() as $product) {
            $products[] = new Entity\Product(
                (int)$product["product_id"],
                $product["name"],
                $product["code"],
                self::getFileStoreRoot().$product["picture"],
                $product["description"],
                null
            );
        }

        return ProductsFactory::createByObjects($products);
    }

    /**
     * @inheritdoc
     * @throws
     */
    public function addFlush(int $userId, int $productId): Entity\ProductCollection
    {
       if($this->getRowsCount($userId, $productId)) {
           throw new ItemExists($userId, $productId);
       }

       $sql = $this->getAddFlushSql($userId, $productId);
       $this->db->query($sql);


       return $this->getByUserId($userId);
    }

    /**
     * @inheritdoc
     * @throws
    */
    public function removeFlush(int $userId, int $productId): Entity\ProductCollection
    {
        $rowsCount = $this->getRowsCount($userId, $productId);

        if(!$rowsCount) {
            throw new ItemDoesntExist($userId, $productId);
        }
        elseif($rowsCount > 1) {
            throw new ItemDuplicates($userId, $productId);
        }

        $sql = $this->getRemoveFlushSql($userId, $productId);
        $this->db->query($sql);

        return $this->getByUserId($userId);
    }

    /**
     * @inheritdoc
     * @throws
     */
    public function removeAllFlush(int $userId): Entity\ProductCollection {
        if(!$this->getRowsCount($userId)) {
            throw new ItemsDontExist($userId);
        }

        $sql = $this->getRemoveFlushSql($userId);
        $this->db->query($sql);

        return ProductsFactory::createByObjects([]);
    }

    /**
     * @param int $userId
     * @param int|null $productId
     * @return int
     * @throws
     */
    private function getRowsCount(int $userId, int $productId = null) {
        $sql = $this->getRowsCountSql($userId, $productId);
        return (int)$this->db->query($sql)->fetch()['count'];
    }

    /**
     * @param int $userId
     * @return string
     */
    private function getByUserIdSql(int $userId): string {

        return " SELECT t1.*, "
            ." t2.NAME AS name, "
            ." t2.CODE as code, "
            ." t2.PREVIEW_TEXT AS description, "
            ." CONCAT (t3.SUBDIR, '/', t3.FILE_NAME) AS picture "
            ." FROM {$this->cartsTableName} AS t1 "
            ." LEFT JOIN {$this->elementsTableName} AS t2 ON t1.product_id = t2.ID "
            ." LEFT JOIN {$this->filesTableName} AS t3 ON t2.PREVIEW_PICTURE = t3.ID "
            ." WHERE t1.user_id =  $userId ";
    }

    /**
     * @param int $userId
     * @param int|null $productId
     * @return string
     */
    private function getRowsCountSql(int $userId, int $productId = null): string {
        $sql = "SELECT COUNT(id) AS `count` FROM {$this->cartsTableName} ".
               "WHERE user_id = $userId";
        return ($productId) ? $sql." AND product_id = $productId" : $sql;
    }

    /**
     * @param int $userId
     * @param int $productId
     * @return string
     */
    private function getAddFlushSql(int $userId, int $productId): string {
        return "INSERT INTO {$this->cartsTableName} (user_id, product_id) VALUES ($userId, $productId)";
    }

    /**
     * @param int $userId
     * @param int|null $productId
     * @return string
     */
    private function getRemoveFlushSql(int $userId, int $productId = null): string {
        $sql = "DELETE FROM {$this->cartsTableName} WHERE user_id = $userId";
        return ($productId) ? $sql." AND product_id = $productId" : $sql;
    }

    private static function getFileStoreRoot() {
        return "/upload/";
    }
}