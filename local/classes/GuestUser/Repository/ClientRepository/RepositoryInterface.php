<?php

namespace Oip\GuestUser\Repository\ClientRepository;


interface RepositoryInterface
{
    public function getData(): ?string;
    public function setData($id): void;
}