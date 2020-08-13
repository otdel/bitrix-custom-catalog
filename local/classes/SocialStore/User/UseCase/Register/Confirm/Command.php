<?php


namespace Oip\SocialStore\User\UseCase\Register\Confirm;


class Command
{
    /** @var int $userId */
    public $userId;

    /** @var string $verificationCode */
    public $verificationCode;
}