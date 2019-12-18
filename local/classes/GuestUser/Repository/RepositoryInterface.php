<?php

namespace Oip\GuestUser\Repository;


interface RepositoryInterface
{
    public function getData(): ?string;
    public function setData($id): void;
}