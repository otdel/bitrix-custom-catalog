<?php

namespace Oip\DataMover\Repository;

interface RepositoryInterface
{

    /**
     * @param $entityName string
     * @param $guestId int
     * @param $userId int
     * @return array
     */
    public function getRecords(string $entityName, int $guestId, int $userId): array;

    /**
     * @param string $entityName
     * @param array $records
     * @param int $guestId
     * @param int $userId
     * @return int
    */
    public function updateNonDuplicateRecords(string $entityName, array $records, int $guestId, int $userId): int;

    /**
     * @param string $query
     */
    public function executeQuery(string $query);
}