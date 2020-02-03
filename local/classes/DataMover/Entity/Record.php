<?php


namespace Oip\DataMover\Entity;


class Record
{
    /** @var $id int */
    private $id;

    /** @var array $uniqueValues */
    private $uniqueValues;

    /**
     * @param int $id
     * @param array $uniqueValues
     */
    public function __construct(int $id, array $uniqueValues)
    {
        $this->id = $id;
        $this->uniqueValues = $uniqueValues;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getUniqueValues(): array
    {
        return $this->uniqueValues;
    }


}