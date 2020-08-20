<?php


namespace Oip\Event\Handler\Bitrix\DataMover;

use Bitrix\Main\Application;
use Bitrix\Main\Config\Configuration;
use Bitrix\Main\DB\SqlQueryException;

use Oip\DataMover\Repository\DBRepository as MoverRepository;
use Oip\DataMover\Handler as Mover;

use Oip\DataMover\Entity\RecordType;
use Oip\DataMover\Exception\WrongDuplicatesNumber as WrongDuplicatesNumberException;

use Oip\SocialStore\User\Repository\NotFoundException;
use Oip\SocialStore\User\Repository\UserRepository;
use Oip\Util\Bitrix\DateTimeConverter;
use Oip\SocialStore\User\Entity\User;

class DataMover
{
    public static function onAfterUserAuthorize(array $arUser) {

        global $OipGuestUser;
        global $APPLICATION;

        $storeUser = self::initStoreUser($arUser["user_fields"]);


        $guestId = $OipGuestUser->getUser()->getNegativeId();
        $userId = $arUser["user_fields"]["ID"];

        $connection = Application::getConnection();
        $moverRepository = new MoverRepository($connection);

        $movedCategories = Configuration::getValue("oip_data_mover");

        if(is_null($movedCategories)) {
            throw new GettingConfigException();
        }

        foreach($movedCategories as $category) {

            $entityName = $category["entityName"];
            $uniqueCols = $category["uniqueCols"];

            $currentUserId = $userId;
            if($entityName == "oip_carts") {
                $currentUserId = $storeUser->getId();
            }

            $recordType = new RecordType($entityName, $uniqueCols);
            $mover = new Mover($moverRepository, $guestId, $currentUserId, $recordType);
            $rule = $mover->createHandlingRule();

            $records = $mover->getRecords();
            $duplicateUniqueValues = $mover->getDuplicateUniqueValues($records);
            $allDuplicateIDs = [];
            foreach ($duplicateUniqueValues as $duplicateUniqueSet) {
                try {
                    $duplicatesBySet = $mover->getDuplicateRecordsByUnique($duplicateUniqueSet, $records);
                    $mover->handleRule($rule, $duplicateUniqueSet);
                    $allDuplicateIDs = array_merge($allDuplicateIDs, $mover->getRecordsId($duplicatesBySet));
                }
                catch (WrongDuplicatesNumberException $exception) {
                    $APPLICATION->IncludeComponent("oip:system.exception.viewer","",
                        ["EXCEPTION" => $exception]);

                    continue;
                }
            }

            $nonDuplicateRecords = $mover->getNonDuplicateRecords($records, $allDuplicateIDs);
            $mover->moveNonDuplicateRecords($nonDuplicateRecords);

            unset($nonDuplicateRecords);
            unset($allDuplicateIDs);
            unset($duplicateUniqueValues);
            unset($records);
            unset($rule);
            unset($mover);
            unset($recordType);
        }
    }

    /**
     * @param array $bxUserFields
     * @throws SqlQueryException
     * @return User
     */
    private static function initStoreUser(array $bxUserFields) {
        $userRepository = new UserRepository(Application::getConnection(), new DateTimeConverter());

        try {
            $storeUser = $userRepository->getByBxId((int)$bxUserFields["ID"]);
        }
        catch (NotFoundException $exception) {
            $newStoreUserId = $userRepository->addFromBxUser(
                $bxUserFields["EMAIL"],
                $bxUserFields["PERSONAL_PHONE"],
                (int)$bxUserFields["ID"],
                $bxUserFields["NAME"],
                $bxUserFields["LAST_NAME"],
                $bxUserFields["SECOND_NAME"],
            );
            $userRepository->verifyUserPhone($newStoreUserId);

            $storeUser =  $userRepository->getById($newStoreUserId);
        }
        finally {
            return  $storeUser;
        }
    }

    private static function moveExistUserOrders(int $bxId, User $user) {

    }
}