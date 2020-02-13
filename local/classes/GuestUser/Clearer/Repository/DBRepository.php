<?php

namespace Oip\GuestUser\Clearer\Repository;

use Exception;

use Bitrix\Main\DB\SqlQueryException;
use Oip\GuestUser\Entity\User;
use Oip\GuestUser\Clearer\Entity\ProductView\Record as ProductView;
use Oip\Util\Bitrix\DateTimeConverter;

use Bitrix\Main\DB\Connection;


class DBRepository implements RepositoryInterface {

    /** @var string $guestUsersTableName */
    private $guestUsersTableName = "oip_guest_users";

    /** @var string $productViewsTableName */
    private $productViewsTableName = "oip_product_view";

    /** @var Connection $db */
    private $db;
    /** @var DateTimeConverter $converter */
    private $converter;

    public function __construct(Connection $connection, DateTimeConverter $converter) {
        $this->db = $connection;
        $this->converter = $converter;
    }

    /**
     * @inheritdoc
     * @throws SqlQueryException
     * @throws Exception
     */
    public function getUserById(int $guestId): ?User
    {
        $user = $this->db->query("SELECT * FROM {$this->guestUsersTableName} WHERE id = $guestId")->fetch();

        if(!$user)
            return null;

        return new User($user["id"], $user["hash_id"],
            $this->converter->convertBitrixToNative($user["last_visit"]));
    }

    /**
     * @inheritdoc
     * @throws SqlQueryException
     * @throws Exception
     */
    public function getUserProductViews(int $guestId): array {
        $records = [];
        $res = $this->db->query("SELECT * FROM  {$this->productViewsTableName} WHERE user_id = $guestId");
        while($record = $res->fetch()) {
            $records[] = new ProductView($record["id"], $guestId, $record["product_id"], $record["section_id"],
                $this->converter->convertBitrixToNative($record["date_insert"]),
                $this->converter->convertBitrixToNative($record["date_modify"]),
                $record["views_count"], $record["likes_count"]);
        }

        return $records;
    }

    /**
     *
     * @inheritdoc
     * @throws SqlQueryException
    */
    public function archiveProductViewRecord(ProductView $record): int {

        $affected = 0;

        $sql = "INSERT INTO {$this->productViewsTableName} (user_id, product_id, section_id, views_count, likes_count) "
            ." VALUES (0, {$record->getProductId()}, {$record->getSectionId()},"
            ." {$record->getViewsCount()}, {$record->getLikesCount()})"
            ." ON DUPLICATE KEY UPDATE date_modify = NOW(), views_count = views_count + {$record->getViewsCount()},"
            ." likes_count = likes_count + {$record->getLikesCount()}";
        $this->db->query($sql);

        $affected += $this->db->getAffectedRowsCount();

        if($affected > 0) {
            $this->db->query("DELETE from {$this->productViewsTableName} WHERE user_id = {$record->getUserId()}"
                ." AND product_id = {$record->getProductId()} AND section_id = {$record->getSectionId()}");

            $affected += $this->db->getAffectedRowsCount();
        }

        return ($affected < 2) ? 0 : 1;
    }

    /**
     * @inheritdoc
     * @throws SqlQueryException
     */
    public function dropUser(int $guestId) {
        $this->db->query("DELETE FROM {$this->guestUsersTableName} where id = $guestId");

        return $this->db->getAffectedRowsCount();
    }
}