<?php

namespace Oip\GuestUser;

class User
{
    /** @var integer $id */
    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    /** @return integer $id */
    public function getId(): int {
        return $this->id;
    }

}