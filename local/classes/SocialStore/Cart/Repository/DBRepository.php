<?php

namespace Oip\SocialStore\Cart\Repository;

use Bitrix\Main;
use Oip\SocialStore\Product\Entity;

use Oip\Util\Collection\Factory\CollectionsFactory as ProductsFactory;
use Oip\Util\Collection\Factory\InvalidSubclass as InvalidSubclassException;
use Oip\Util\Collection\Factory\NonUniqueIdCreating as NonUniqueIdCreatingException;

use Oip\SocialStore\Cart\Exception\ItemExists;
use Oip\SocialStore\Cart\Exception\ItemDoesntExist;
use Oip\SocialStore\Cart\Exception\ItemsDontExist;
use Oip\SocialStore\Cart\Exception\ItemDuplicates;

use Oip\Util\Bitrix\Iblock\ElementPath\Helper as ElementPath;
use Bitrix\Main\Loader;
use \CIBlockElement;

class DBRepository implements RepositoryInterface
{
    private $cartsTableName = "oip_carts";
    private $elementsTableName = "b_iblock_element";
    private $filesTableName = "b_file";

    /** @var Main\DB\Connection $db */
    private $db;
    /** @var ElementPath $pathHelper */
    private $pathHelper;

    public function __construct(Main\DB\Connection $connection, ElementPath $pathHelper)
    {
        $this->db = $connection;
        $this->pathHelper = $pathHelper;
    }

    /**
     * @inheritdoc
     *
     * @throws Main\Db\SqlQueryException
     * @throws NonUniqueIdCreatingException
     * @throws InvalidSubclassException
     * @throws Main\LoaderException
     */
    public function getByUserId($userId): Entity\ProductCollection
    {

        $products = [];
        $sql = self::getByUserIdSql($userId);

        $arProducts = $this->db->query($sql)->fetchAll();
        $productIds = array_map(function ($product) {
            return (int)$product["product_id"];
        }, $arProducts);
        $productProps = $this->getBXProductProps($productIds);

        foreach($arProducts as $product) {
            $productName = (!is_null($product["name"])) ? $product["name"] : "deleted";
            $productPicture = (!is_null($product["name"])) ? self::getFileStoreRoot().$product["picture"] : null;

            $products[] = new Entity\Product(
                $id = (int)$product["product_id"],
                $productProps[$id]["article"] ?? "",
                $productName,
                $product["code"],
                $productPicture,
                $product["description"],
                $this->pathHelper->makeUrl((int)$product["product_id"])
            );
        }

        return ProductsFactory::createByObjects($products, "Oip\SocialStore\Product\Entity\ProductCollection");
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

        return ProductsFactory::createByObjects([], "Oip\SocialStore\Product\Entity\ProductCollection");
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

    /**
     * @param array $productIDs
     * @return array
     * @throws Main\LoaderException
     */
    private function getBXProductProps($productIDs) {

        Loader::includeModule("iblock");

        $props = [];

        $dbRes = CIBlockElement::GetList(
            [],
            ["IBLOCK_ID" => $this->pathHelper->getIblockId(),"ID" => $productIDs],
            false,
            false,
            ["ID","IBLOCK_ID","PROPERTY_ARTICLE"]
        );

        while($product = $dbRes->GetNext()) {
            $props[(int)$product["ID"]]["article"] = $product["PROPERTY_ARTICLE_VALUE"];
        }

        return $props;
    }
}