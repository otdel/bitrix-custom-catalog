<?php

namespace Oip\Model\GuestUser\Repository;


interface RepositoryInterface
{
    public function getData(): ?string;
    public function setData($id): void;
}