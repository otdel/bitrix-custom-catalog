<?php
namespace Oip\Custom\Component\Iblock;

class ReturnedData
{
    private $pagination;
    private $componentID;

    public function __construct(array $pagination, $componentID)
    {
        $this->pagination = $pagination;
        $this->componentID = $componentID;
    }

    /**
     * @return array
     */
    public function getPagination(): array
    {
        return $this->pagination;
    }

    /**
     * @return int
     */
    public function getComponentID(): int
    {
        return $this->componentID;
    }


}