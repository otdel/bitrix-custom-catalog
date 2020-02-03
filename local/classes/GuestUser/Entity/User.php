<?php

namespace Oip\GuestUser\Entity;

class User
{
    /** @var int $id */
    private $id;

    /** @var string $hashId */
    private $hashId;

    public function __construct(int $id, string $hashId)
    {
        $this->id = $id;
        $this->hashId = $hashId;
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
}