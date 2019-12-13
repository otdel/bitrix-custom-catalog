<?php

namespace Oip\Model\GuestUser\IdGenerator;

interface IdGeneratorInterface
{
    public function generateId(): int;
}