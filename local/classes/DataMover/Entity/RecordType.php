<?php


namespace Oip\DataMover\Entity;


class RecordType
{
    /** @var string $entityName */
    private $entityName;

   /** @var array $uniqueNames */
    private $uniqueCols;

    /**
     * @param string $entityName
     * @param array $uniqueCols
     */
    public function __construct(string $entityName, array $uniqueCols)
    {
        $this->entityName = $entityName;
        $this->uniqueCols = $uniqueCols;
    }

    /**
     * @return string
     */
    public function getEntityName(): string
    {
        return $this->entityName;
    }

    /**
     * @return array
     */
    public function getUniqueCols(): array
    {
        return $this->uniqueCols;
    }
}