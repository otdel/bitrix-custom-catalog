<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

\CBitrixComponent::includeComponentClass("oip:component");

use Bitrix\Main\SystemException;
use Oip\GuestUser\Handler as GuestUser;

use Oip\GuestUser\Repository\ClientRepository\Exception\UserDoesntExist as UserDoesntExistException;

class COipGuestUserProcessorWrite extends \COipComponent
{
    public function executeComponent(): void
    {
        if(!is_set($GLOBALS["OipGuestUser"])) {
            throw new SystemException("The component oip:guest.user.processor.init wasn't running");
        }

        /**
         * @var $OipGuestUser GuestUser
         */
        global $OipGuestUser;
        try {
            $OipGuestUser->setUser();
        }
        catch (UserDoesntExistException $exception) {
            global $APPLICATION;
            $APPLICATION->IncludeComponent("oip:system.exception.viewer","",[
                "EXCEPTION" => $exception
            ]);
        }
    }
}
