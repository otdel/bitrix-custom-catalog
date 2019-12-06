<?php

namespace Oip\GuestUser\IdGenerator;

interface IdGeneratorInterface
{
    public function generateId(): int;
}