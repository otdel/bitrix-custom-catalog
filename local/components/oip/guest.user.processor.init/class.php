<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Application;
use Bitrix\Main\Config\Configuration;
use Bitrix\Main\SystemException;
use Bitrix\Main\DB\SqlQueryException;

use Oip\GuestUser\Handler as GuestUser;
use Oip\GuestUser\Repository\ClientRepository\CookieRepository;
use Oip\GuestUser\Repository\ServerRepository\DBRepository;

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

        $clientRepository = new CookieRepository($cookieName, $cookieExpired, $siteName);
        $serverRepository = new DBRepository($connection);

        $user = new GuestUser($clientRepository, $serverRepository);

        try {
            $user->getUser();
        }
        catch(SqlQueryException $exception) {
            global $APPLICATION;
            $APPLICATION->IncludeComponent("oip:system.exception.viewer","",[
                "EXCEPTION" => $exception
            ]);
        }

        $GLOBALS["OipGuestUser"] = $user;
    }
}