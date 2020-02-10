<?php

namespace Oip\GuestUser\Entity;

use DateTime;

class User
{
    /** @var int $id */
    private $id;

    /** @var string $hashId */
    private $hashId;

    /** @var DateTime $lastVisit */
    private $lastVisit;

    public function __construct(int $id, string $hashId, DateTime $lastVisit)
    {
        $this->id = $id;
        $this->hashId = $hashId;
        $this->lastVisit = $lastVisit;
    }

    /** @return int $id */
    public function getId(): int {
        return $this->id;
    }

    /** @return int $id */
    public function getNegativeId(): int {
        return (0 - $this->id);
    }

    /** @return string $hashId */
    public function getHashId(): string {
        return $this->hashId;
    }

    /**
     * @return DateTime
     */
    public function getLasVisit(): DateTime
    {
        return $this->lastVisit;
    }


}