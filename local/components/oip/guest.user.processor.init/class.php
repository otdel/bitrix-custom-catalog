<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Application;
use Bitrix\Main\Config\Configuration;
use Bitrix\Main\SystemException;

use Oip\GuestUser\Handler as GuestUser;
use Oip\GuestUser\Repository\CookieRepository as GuestUserRepository;
use Oip\GuestUser\UserGenerator\DBUserGenerator;

\CBitrixComponent::includeComponentClass("oip:component");

class COipGuestUserProcessorInit extends \COipComponent
{
    /**
     * @inheritDoc
     * @throws SystemException
     * @return void
    */
    public function executeComponent(): void
    {
        $cookieName = Configuration::getValue("oip_guest_user")["cookieName"];
        $cookieExpired = Configuration::getValue("oip_guest_user")["cookieExpired"];

        $siteName = Application::getInstance()->getContext()->getServer()->getServerName();
        $connection = Application::getConnection();

        $repository = new GuestUserRepository($cookieName, $cookieExpired, $siteName);
        $userGenerator = new DBUserGenerator($connection);

        $user = new GuestUser($repository, $userGenerator);
        $user->getUser();
        $GLOBALS["OipGuestUser"] = $user;
    }
}