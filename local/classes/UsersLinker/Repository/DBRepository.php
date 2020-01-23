<?php


namespace Oip\UsersLinker\Repository;

use Bitrix\Main\DB\Connection;
use Bitrix\Main\DB\SqlQueryException;

use Oip\UsersLinker\Repository\Exception\CreatingGuestToAuthorizedLink as CreatingGuestToAuthorizedLinkException;
use Oip\UsersLinker\Repository\Exception\DuplicateLink as DuplicateLinkException;

class DBRepository implements RepositoryInterface
{
    /** @var $guestToAuthorizedLinksTableName string */
    private $guestToAuthorizedLinksTableName = "oip_guest_to_authorized_links";

    /** @var $db Connection */
    private $db;

    public function __construct(Connection $connection)
    {
        $this->db = $connection;
    }

    /**
     * @inheritDoc
     * @throws CreatingGuestToAuthorizedLinkException
     * @throws DuplicateLinkException
     * @throws SqlQueryException
     */
    public function addUsersLink(int $guestUserId, int $authorizedUserId): int
    {
        if($this->isLinkExists($guestUserId, $authorizedUserId)) {
            throw new DuplicateLinkException($guestUserId, $authorizedUserId);
        }

        $this->db->query("INSERT INTO {$this->guestToAuthorizedLinksTableName} (`guest_id`, `authorized_id`) "
            ."VALUE ($guestUserId, $authorizedUserId) ");

        if($this->db->getAffectedRowsCount() === 0) {
            throw new CreatingGuestToAuthorizedLinkException($guestUserId, $authorizedUserId);
        }

        return $this->db->getInsertedId();
    }

    /**
     * @inheritDoc
     * @throws SqlQueryException
     */
    public function isLinkExists(int $guestUserId, int $authorizedUserId): bool {
        $res = $this->db->query("SELECT id FROM {$this->guestToAuthorizedLinksTableName} "
            ."WHERE  `guest_id` = '$guestUserId' AND `authorized_id` = '$authorizedUserId'");
        return ($res->getSelectedRowsCount() > 0);
    }
}