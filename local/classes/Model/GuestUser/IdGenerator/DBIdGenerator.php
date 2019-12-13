<?php

namespace Oip\Model\GuestUser\IdGenerator;

use Oip\RelevantProducts\DataWrapper;
use Oip\RelevantProducts\DataSourceInterface;

class DBIdGenerator implements IdGeneratorInterface
{
    /** @var DataSourceInterface */
    private $ds;

    public function __construct(DataSourceInterface $ds)
    {
        $this->ds = $ds;
    }

    /**
     * @return integer
     * @throws \Exception
     */
    public function generateId(): int
    {
        $dw = new DataWrapper($this->ds);
        return $dw->getFreeGuestId();
    }
}