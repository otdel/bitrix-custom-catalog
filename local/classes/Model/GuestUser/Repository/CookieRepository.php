<?php


namespace Oip\Model\GuestUser\Repository;

use Bitrix\Main\Application;
use Bitrix\Main\SystemException;
use Bitrix\Main\Web\Cookie;

class CookieRepository implements RepositoryInterface
{
    const COOKIE_NAME_DEFAULT = "OIP_GUEST_ID";
    const COOKIE_EXPIRE_DEFAULT = 60*60*24*30*3; // 3 месяца

    /** @var string $name */
    private $name;
    /** @var int $expired */
    private $expired;

    public function __construct($name = null, $expired = null)
    {
        $this->name = ($name) ? $name : self::COOKIE_NAME_DEFAULT;
        $this->expired = ($expired) ? $expired : self::COOKIE_EXPIRE_DEFAULT;
    }

    /**
     * @return null|string
     * @throws SystemException
     */
    public function getData(): ?string {
       return $_COOKIE[$this->name];
    }

    /**
     * @var integer $id
     * @throws SystemException
     */
    public function setData($id): void {
        setcookie($this->name, $id,time() + $this->expired, "/", SITE_SERVER_NAME);
    }

}