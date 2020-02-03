<?php


namespace Oip\Event\Handler\Bitrix\UserLinker;

use Exception;

use Bitrix\Main\Application;

use Oip\GuestUser\Handler as GuestUser;
use Oip\UsersLinker\Repository\DBRepository as UsersLinkerRepository;

class UserLinker
{
    public static function onAfterUserAuthorize(array $arUser) {
        /** @var $OipGuestUser GuestUser */
        $connection = Application::getConnection();
        global $OipGuestUser;
        global $APPLICATION;

        $rep = new UsersLinkerRepository($connection);

        $authorizedId = $arUser["user_fields"]["ID"];
        $guestId = $OipGuestUser->getUser()->getId();

        try {
            if(!$rep->isLinkExists($guestId, (int)$authorizedId)) {
                $rep->addUsersLink($guestId, (int)$authorizedId);
            }
        }
        catch(Exception $e) {
            $APPLICATION->IncludeComponent("oip:system.exception.viewer","",[
                "EXCEPTION" => $e
            ]);
        }
    }
}