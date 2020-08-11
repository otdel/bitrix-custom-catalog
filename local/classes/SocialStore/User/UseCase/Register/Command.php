<?php

namespace Oip\SocialStore\User\UseCase\Register;

class Command
{
    /** @var string $email */
    public $email;

    /** @var string $phone */
    public $phone;

    /** @var string $name */
    public $name;

    /** @var string $surname */
    public $surname;

    /** @var string $patronymic */
    public $patronymic;

    /** @var string $password */
    public $password;

    /** @var string $confirmPassword */
    public $confirmPassword;
}