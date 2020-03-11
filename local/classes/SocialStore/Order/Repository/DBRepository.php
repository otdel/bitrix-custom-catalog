<?php

namespace Oip\SocialStore\Order\Repository;

use Exception;
use Oip\SocialStore\Order\Repository\Exception\OrderCreatingError as OrderCreatingErrorException;
use Oip\SocialStore\Order\Repository\Exception\OrderDeletingError as OrderDeletingErrorException;
use Oip\Util\Collection\Factory\NonUniqueIdCreating as NonUniqueIdCreatingException;
use Oip\Util\Collection\Factory\InvalidSubclass as InvalidSubclassException;
use Bitrix\Main;

use Oip\SocialStore\Order\Entity;
use Oip\SocialStore\Order\Status\Entity\Status;
use Oip\SocialStore\User\Entity\User;
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
     * @return Entity\OrderCollection
     * @throws Exception
     * @throws Main\Db\SqlQueryException
     * @throws InvalidSubclassException
     * @throws NonUniqueIdCreatingException
     */
    public function getAllByUserId(int $userId): Entity\OrderCollection
    {
       $sql = $this->getAllByUserIdSql($userId);
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
     * @throws OrderCreatingErrorException
     */
    public function addOrder(Entity\Order $order): int
    {
        $userId = $order->getUser()->getId();
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
     * @param array $order
     * @return Entity\Order
     * @throws Exception
     */
    private function getEntityByRow(array $order): Entity\Order {
        return new Entity\Order(
            new User((int)$order["user_id"]),
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
        return $this->getAllSql(). " WHERE t1.user_id = '$userId' ";
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