<?php

namespace Oip\SocialStore\Order\Repository;

use Exception;
use Oip\SocialStore\Order\Repository\Exception\OrderCreatingError as OrderCreatingErrorException;
use Oip\SocialStore\Order\Repository\Exception\OrderDeletingError as OrderDeletingErrorException;
use Oip\SocialStore\Order\Repository\Exception\OrderUpdatingStatusError as OrderUpdatingStatusErrorException;
use Oip\Util\Collection\Factory\NonUniqueIdCreating as NonUniqueIdCreatingException;
use Oip\Util\Collection\Factory\InvalidSubclass as InvalidSubclassException;
use Bitrix\Main;

use Oip\SocialStore\Order\Entity;
use Oip\SocialStore\Order\Status\Entity\Status;
use Oip\SocialStore\Order\Repository\Exception\NonExsistentOrderId;

use Oip\Util\Serializer\ObjectSerializer\SerializerInterface;
use Oip\Util\Bitrix\DateTimeConverter;
use Oip\Util\Collection\Factory\CollectionsFactory;

/**
 * Class DBRepository
 * @package Oip\SocialStore\Order\Repository
 */
class DBRepository implements RepositoryInterface
{

    /** @var string $ordersTableName */
    private $ordersTableName = "oip_orders";
    /** @var string $statusesTableName */
    private $statusesTableName = "oip_order_statuses";

    /** @var Main\DB\Connection $db */
    private $db;

    /** @var SerializerInterface $serializer */
    private $serializer;

    /** @var DateTimeConverter $datetimeConverter */
    private $datetimeConverter;
    /** @var CollectionsFactory $collectionFactory */
    private $collectionFactory;

    /**
     * @param Main\DB\Connection $connection
     * @param SerializerInterface $serializer
     * @param DateTimeConverter $bitrixToNativeConverter
     * @param CollectionsFactory $collectionFactory
     */
    public function __construct(Main\DB\Connection $connection, SerializerInterface $serializer,
                                DateTimeConverter $bitrixToNativeConverter, CollectionsFactory $collectionFactory)
    {
        $this->db = $connection;
        $this->serializer = $serializer;
        $this->datetimeConverter = $bitrixToNativeConverter;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @inheritdoc
     * @throws Exception
     * @throws Main\DB\SqlQueryException
     * @throws NonExsistentOrderId
     */
    public function getById(int $orderId): Entity\Order
    {
        $sql = $this->getByIdSql($orderId);
        $order = $this->db->query($sql)->fetch();

        if(!$order) {
            throw new NonExsistentOrderId($orderId);
        }

        return $this->getEntityByRow($order);
    }


    /**
     * @param int $userId
     * @param int $page
     * @param int|null $onPage
     * @return Entity\OrderCollection
     * @throws InvalidSubclassException
     * @throws Main\Db\SqlQueryException
     * @throws NonUniqueIdCreatingException
     */
    public function getAllByUserId(int $userId, $page = 1, $onPage = null): Entity\OrderCollection
    {
       $sql = $this->getAllByUserIdSql($userId) . $this->getPageSql($page, $onPage);
       $arOrders = $this->db->query($sql)->fetchAll();
       $objOrders = [];

       foreach($arOrders as $order) {
           $objOrders[] = $this->getEntityByRow($order);
       }

        return $this->collectionFactory::createByObjects($objOrders, "Oip\SocialStore\Order\Entity\OrderCollection");
    }

    /**
     * @inheritdoc
     * @throws Main\DB\SqlQueryException
     */
    public function getCountByUserId(int $userId): int {
        $sql = "SELECT count(id) as 'count' from {$this->ordersTableName} WHERE user_id = '$userId'";
        return (int)$this->db->query($sql)->fetch()["count"];
    }

    /**
     * @inheritdoc
     * @throws Main\DB\SqlQueryException
     * @throws OrderCreatingErrorException
     */
    public function addOrder(Entity\Order $order): int
    {
        $userId = $order->getUserId();
        $statusId = $order->getStatus()->getId();
        $products = $this->serializer->serialize($order->getProducts());

        $sql = $this->getAddOrderSql($userId, $statusId, $products);
        $this->db->query($sql);

        if($this->db->getAffectedRowsCount() === 0) {
            throw new OrderCreatingErrorException();
        }

        return $this->db->getInsertedId();
    }

    /**
     * @inheritdoc
     * @throws Main\DB\SqlQueryException
     * @throws OrderDeletingErrorException
     */
    public function removeOrder(int $orderId): int
    {
       $sql = $this->removeOrderSql($orderId);
       $this->db->query($sql);

       if($this->db->getAffectedRowsCount() === 0) {
           throw new OrderDeletingErrorException($orderId);
       }

       return $this->db->getAffectedRowsCount();
    }

    /**
     * @inheritDoc
     * @throws Main\DB\SqlQueryException
     * @throws OrderUpdatingStatusErrorException
     */
    public function updateStatus(int $orderId, int $statusId): void {
        $sql = "UPDATE {$this->ordersTableName} SET status_id = $statusId WHERE id = $orderId";
        $this->db->query($sql);

        if ($this->db->getAffectedRowsCount() == 0) {
            throw new OrderUpdatingStatusErrorException(
                "An error occurred while updating status to '$statusId' of the order '$orderId'");
        }
    }

    /**
     * @param array $order
     * @return Entity\Order
     * @throws Exception
     */
    private function getEntityByRow(array $order): Entity\Order {
        return new Entity\Order(
            (int)$order["user_id"],
            new Status((int)$order["status_id"], $order["status_code"], $order["status_label"]),
            $this->serializer->deserialize($order["products"]),
            (int)$order["id"],
            $this->datetimeConverter->convertBitrixToNative($order["date_create"])
        );
    }

    /**
     * @param int $userId
     * @param int $statusId
     * @param string $products
     * @return string
     */
    private function getAddOrderSql(int $userId, int $statusId, string $products): string
    {
        return "INSERT INTO {$this->ordersTableName} (user_id, status_id, products) VALUE ($userId, $statusId, '$products')";
    }

    /**
     * @param int $orderId
     * @return string
     */
    private function getByIdSql(int $orderId): string
    {
        return $this->getAllSql(). " WHERE t1.id = '$orderId' ";
    }

    /**
     * @param int $userId
     * @return string
     */
    private function getAllByUserIdSql(int $userId): string {
        return $this->getAllSql(). " WHERE t1.user_id = '$userId' " . $this->getSortDateDesc();
    }

    /**
     * @return string
     */
    private function getSortDateDesc() {
        return " ORDER BY `date_create` DESC";
    }

    /**
     * @param int $page
     * @param null $onPage
     * @return string
    */
    private function getPageSql($page = 1, $onPage = null) {
        if(!$onPage) return "";

        $offset = $onPage*($page-1);
        return " LIMIT $offset, $onPage";
    }

    /**
     * @return string
     */
    private function getAllSql() {
        return " SELECT t1.*, t2.code as status_code, t2.label as status_label FROM {$this->ordersTableName} AS t1 "
            . " LEFT JOIN {$this->statusesTableName} as t2 ON t1.status_id = t2.id";
    }

    /**
     * @param int $orderId
     * @return string
     */
    private function removeOrderSql(int $orderId): string {
        return "DELETE FROM {$this->ordersTableName} WHERE id = $orderId";
    }
}