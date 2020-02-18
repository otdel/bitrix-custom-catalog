<?php

use Bitrix\Main\Application;

use Oip\GuestUser\Clearer\Handler as Clearer;
use Oip\GuestUser\Clearer\Repository\DBRepository;
use Oip\Util\Bitrix\DateTimeConverter;
use Oip\GuestUser\Clearer\Exception\UserIsntExpired as UserIsntExpiredException;

$_SERVER["DOCUMENT_ROOT"] = __DIR__ . "/../../../";
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];
require $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php";

@set_time_limit(0);
@ignore_user_abort(true);

try {
    $connection = Application::getConnection();
    $converter = new DateTimeConverter();

    $repository = new DBRepository($connection, $converter);
    $clearer = new Clearer($repository);

    $affected = 0;
    $notExpired = 0;

    echo "Guest archiving has started\n\n";

    $guestIds = $clearer->getAllGuestId();
    echo "Total guest count: ".count($guestIds)."\n\n";

    foreach($guestIds as $guestId) {
        try {
            $expiredUser = $clearer->getExpiredUser($guestId);
            $views = $clearer->getUserProductViews($expiredUser);
            $clearer->archiveProductViews($views);
            $clearer->deleteExpiredUser($expiredUser);

            $affected++;

            echo "Guest $guestId has been archived\n";
        }
        catch (UserIsntExpiredException $e) {
            $notExpired++;
            echo $e->getMessage()."\n";
        }
    }

    echo "\nGuest archiving has finished. $affected guests has been archived.\n";
    echo "Unexpired guest count: $notExpired.";
}
catch(Exception $e) {
    echo $e->getMessage()." Aborting process.";
}

