<?php
namespace Oip\Custom\Component\Iblock;

class ReturnedData
{
    private $pagination;

    public function __construct(array $pagination)
    {
        $this->pagination = $pagination;
    }

    /**
     * @return array
     */
    public function getPagination(): array
    {
        return $this->pagination;
    }
}