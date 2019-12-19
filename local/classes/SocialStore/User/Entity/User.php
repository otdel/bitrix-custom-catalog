<?php

namespace Oip\SocialStore\User\Entity;

class User
{
    /** @var int $id */
    private $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}