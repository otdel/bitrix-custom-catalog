<?php


namespace Oip\GuestUser\Repository\ClientRepository;

class CookieRepository implements RepositoryInterface
{
    const COOKIE_NAME_DEFAULT = "OIP_GUEST_ID";
    const COOKIE_EXPIRE_DEFAULT = 60*60*24*30*3; // 3 месяца

    /** @var string $name */
    private $name;
    /** @var int $expired */
    private $expired;
    /** @var string $domain */
    private $domain;

    public function __construct($name = null, $expired = null, $domain)
    {
        $this->name = ($name) ? $name : self::COOKIE_NAME_DEFAULT;
        $this->expired = ($expired) ? $expired : self::COOKIE_EXPIRE_DEFAULT;
        $this->domain = $domain;
    }

    /**
     * @return null|string
     */
    public function getData(): ?string {
       return $_COOKIE[$this->name];
    }

    /**
     * @var string $id
     */
    public function setData($id): void {
        setcookie($this->name, $id,time() + $this->expired, "/", $this->domain);
    }

}