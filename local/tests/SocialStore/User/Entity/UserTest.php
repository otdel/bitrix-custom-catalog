<?php

namespace Test\SocialStore\User\Entity;

use Oip\SocialStore\User\Entity\Exception\AlreadyVerifiedException;
use Oip\SocialStore\User\Entity\Exception\IncorrectVerificationProcessException;
use Oip\SocialStore\User\Entity\Exception\VerificationCodeExpiredException;
use Oip\SocialStore\User\Entity\Exception\VerificationFailedException;
use PHPUnit\Framework\TestCase;
use Test\Factory\UserFactory;
use DateTimeImmutable;

class UserTest extends  TestCase
{
    public function testGenerateVerificationCode(): void
    {
        $user = UserFactory::create()
            ->withVerifiedPhone()
            ->buildUser();

        $this->expectException(AlreadyVerifiedException::class);
        $user->generateVerificationCode();
    }

    public function testCheckVerificationNotStart(): void
    {
        $user = UserFactory::create()
            ->buildUser();

        $this->expectException(IncorrectVerificationProcessException::class);
        $user->checkVerification("123456", new DateTimeImmutable());
    }

    public function testCheckVerificationExpired(): void
    {
        $user = UserFactory::create()
            ->withVerificationCode()
            ->withExpiredlVerificationDate()
            ->buildUser();

        $this->expectException(VerificationCodeExpiredException::class);
        $user->checkVerification($user->generateVerificationCode(), new DateTimeImmutable());
    }

    public function testCheckVerificationFailed(): void
    {
        $user = UserFactory::create()
            ->withVerificationCode()
            ->withActualVerificationDate()
            ->buildUser();

        $this->expectException(VerificationFailedException::class);
        $user->checkVerification("123456", new DateTimeImmutable());
    }
}