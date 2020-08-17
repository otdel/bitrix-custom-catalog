<?php


namespace Oip\SocialStore\User\UseCase\Register\Confirm;


class Command
{
    /** @var string $userPhone */
    public $userPhone;

    /** @var string $verificationCode */
    public $verificationCode;
}