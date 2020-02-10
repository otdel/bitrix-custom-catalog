<?php


namespace Oip\DataMover;

use Oip\DataMover\Entity\Record;
use Oip\DataMover\Entity\RecordType;

use Oip\DataMover\Exception\WrongDuplicatesNumber;
use Oip\DataMover\Exception\WrongDuplicatesNumber as WrongDuplicatesNumberException;
use Oip\DataMover\Repository\RepositoryInterface as MoverRepository;
use Oip\DataMover\Rule\Rule;

use Oip\DataMover\Rule\DB\Cart\RuleFactory as CartRuleFactory;
use Oip\DataMover\Rule\DB\ProductView\RuleFactory as ProductViewRuleFactory;

use Oip\DataMover\Rule\DB\Cart\RuleHandler as CartRuleHandler;
use Oip\DataMover\Rule\DB\ProductView\RuleHandler as ProductViewRuleHandler;

class Handler
{

    /** @var $repository MoverRepository */
    private $repository;
    /** @var $guestId int */
    private $guestId;
    /** @var $userId int */
    private $userId;

    /** @var RecordType $recordType*/
    private $recordType;

    public function __construct(MoverRepository $repository, int $guestId, int $userId, RecordType $recordType)
    {
        $this->repository = $repository;
        $this->guestId = $guestId;
        $this->userId = $userId;
        $this->recordType = $recordType;
    }

    /** @inheritDoc */
    public function getRecords(): array
    {
        $arRecords =  $this->repository->getRecords($this->recordType->getEntityName(), $this->guestId, $this->userId);
        $records = [];

        foreach ($arRecords as $record) {
            $id = $record["id"];
            $uniqueValues = [];
            foreach ($this->recordType->getUniqueCols() as $colName) {
                $uniqueValues[$colName] = $record[$colName];
            }
            $records[]  =  new Record($id, $uniqueValues);
        }

        return $records;
    }

    /** @inheritDoc */
    public function getDuplicateUniqueValues(array $records): array
    {
        $duplicateValues = [];

        /** @var $record Record */
        /** @var $comparedRecord Record */
        foreach($records as $i => $record) {

            $uniqueValues = $record->getUniqueValues();

            if(in_array($uniqueValues, $duplicateValues)) {
                continue;
            }

            foreach (array_slice($records, $i+1) as $comparedRecord) {
                $comparedUniqueValues = $comparedRecord->getUniqueValues();
                if($uniqueValues === $comparedUniqueValues) {
                    $duplicateValues[] = $uniqueValues;
                    break;
                }
            }

        }

        return  $duplicateValues;
    }

    /**
     * @inheritDoc
     * @throws WrongDuplicatesNumber
     */
    public function getDuplicateRecordsByUnique(array $duplicateUniqueSet, array $records): array {
        $duplicatesByUnique =  array_filter($records, function (Record $record) use($duplicateUniqueSet) {
            return ($duplicateUniqueSet == $record->getUniqueValues());
        });

        if(count($duplicatesByUnique) > 2) {
            $wrongDuplicateSetIds = $this->getRecordsId($duplicatesByUnique);
            throw new WrongDuplicatesNumberException($this->recordType->getEntityName(), implode(",", $wrongDuplicateSetIds));
        }

        return $duplicatesByUnique;
    }

    /** @inheritDoc */
    public function getRecordsId(array $records): array {
       return array_map(function (Record $record) {
           return $record->getId();
       }, $records);

    }

    /** @inheritDoc */
    public function getNonDuplicateRecords(array $records, array $allDuplicateIds): array
    {
        return array_filter($records, function ($record) use($allDuplicateIds) {
            /** @var $record Record */
            return (!in_array($record->getId(),$allDuplicateIds));
        });
    }

    /** @inheritDoc */
    public function moveNonDuplicateRecords(array $records): int {

        $recordIds = $this->getRecordsId($records);

        if(empty($recordIds)) {
           return 0;
        }
        else {
            return $this->repository->updateNonDuplicateRecords($this->recordType->getEntityName(), $recordIds,
                $this->guestId, $this->userId);
        }
    }

    /** @inheritDoc */
    public function createHandlingRule(): Rule {
        switch($this->recordType->getEntityName()) {

            default:
                $rule =  CartRuleFactory::buildRule();
            break;

            case "oip_product_view":
                $rule =  ProductViewRuleFactory::buildRule();
            break;
        }

        return $rule;
    }

    /** @inheritDoc */
    public function handleRule(Rule $rule, array $uniqueSet)
    {
        switch($this->recordType->getEntityName()) {
            case "oip_carts":
                (new CartRuleHandler($this->repository))->handleRule($rule, $uniqueSet, $this->guestId, $this->userId);
            break;

            case "oip_product_view":
                (new ProductViewRuleHandler($this->repository))->handleRule($rule, $uniqueSet,
                    $this->guestId, $this->userId);
            break;
        }
    }
}