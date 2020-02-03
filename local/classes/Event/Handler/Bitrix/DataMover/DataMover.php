<?php


namespace Oip\Event\Handler\Bitrix\DataMover;

use Bitrix\Main\Application;
use Bitrix\Main\Config\Configuration;
use Oip\GuestUser\Handler as GuestUser;

use Oip\DataMover\Repository\DBRepository as MoverRepository;
use Oip\DataMover\Handler as Mover;

use Oip\DataMover\Entity\RecordType;
use Oip\DataMover\Exception\WrongDuplicatesNumber as WrongDuplicatesNumberException;

class DataMover
{
    public static function onAfterUserAuthorize(array $arUser) {
        /** @var $OipGuestUser GuestUser */
        global $OipGuestUser;
        global $APPLICATION;

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

            $recordType = new RecordType($entityName, $uniqueCols);
            $mover = new Mover($moverRepository, $guestId, $userId, $recordType);
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
}