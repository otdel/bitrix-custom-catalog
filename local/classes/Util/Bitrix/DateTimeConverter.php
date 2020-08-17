<?php


namespace Oip\Util\Bitrix;

use Bitrix\Main\Type\DateTime;
use Exception;

class DateTimeConverter
{
    /**
     * @param DateTime $dateTime
     * @return \DateTime
     * @throws Exception
     */
    public function convertBitrixToNative(DateTime $dateTime): \DateTime {
        return (new \DateTime())->setTimestamp($dateTime->getTimestamp());
    }

    /**
     * @param DateTime $dateTime
     * @return \DateTimeImmutable
     * @throws Exception
     */
    public function convertBitrixToImmutable(DateTime $dateTime): \DateTimeImmutable {
        return (new \DateTimeImmutable())->setTimestamp($dateTime->getTimestamp());
    }
}