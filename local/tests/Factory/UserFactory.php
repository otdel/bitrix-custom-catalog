<?php

namespace Test\Factory;

use Oip\SocialStore\User\Entity\User;
use DateTimeImmutable;
use Faker;

class UserFactory
{
    /** Factory $faker */
    private $faker;

    /** @var int $id */
    private $id;

    /** @var string $email */
    private $email;

    /** @var string $phone */
    private $phone;

    /** @var string $name */
    private $name;

    /** @var string|null $surname */
    private $surname;

    /** @var string|null $patronymic */
    private $patronymic;

    /* @var int $bxId */
    private $bxId;

    /** @var int $phoneVerified */
    private $phoneVerified;

    /** @var string|null $verificationCode */
    private $verificationCode;

    /** @var DateTimeImmutable|null $verificationDateExpired */
    private $verificationDateExpired;


    public function __construct(
        Faker\Generator $faker,
        int $id,
        string $email,
        string $phone,
        int $bxId,
        int $phoneVerified,
        string $name,
        ?string $surname = null,
        ?string $patronymic = null,
        ?string $verificationCode = null,
        ?Faker\Generator $verificationDateExpired = null
    )
    {
        $this->faker = $faker;
        $this->id = $id;
        $this->email = $email;
        $this->phone = $phone;
        $this->bxId = $bxId;
        $this->phoneVerified = $phoneVerified;
        $this->name = $name;
        $this->surname = $surname;
        $this->patronymic = $patronymic;
        $this->verificationCode = $verificationCode;
        $this->verificationDateExpired = $verificationDateExpired;
    }


    public static function create(): self {
        $faker = Faker\Factory::create("ru_RU");
        $fullName = explode(" ",$faker->name);

        return new self(
            $faker,
            $faker->numberBetween(1,9999),
            $faker->email,
            $faker->phoneNumber,
            $faker->numberBetween(1,9999),
            0,
            $fullName[0],
            $fullName[2],
            $fullName[1]
        );
    }

    public function withVerifiedPhone(): self {
        $clone = clone $this;
        $clone->phoneVerified = 1;

        return $clone;
    }
    
    public function withVerificationCode(): self {
        $clone = clone $this;

        $clone->verificationCode = str_pad(rand(0,999999), 6, "0", STR_PAD_LEFT);
        
        return $clone;
    }
    
    public function withActualVerificationDate(): self {
        $clone = clone $this;
        
        $clone->verificationDateExpired = (new DateTimeImmutable())->modify("+1 hour");

        return $clone;
    }

    public function withExpiredlVerificationDate(): self {
        $clone = clone $this;

        $clone->verificationDateExpired = (new DateTimeImmutable())->modify("-1 hour");

        return $clone;
    }

    public function buildUser(): User {
        return new User(
            $this->id,
            $this->email,
            $this->phone,
            $this->bxId,
            $this->phoneVerified,
            $this->name,
            $this->surname,
            $this->patronymic,
            $this->verificationCode,
            $this->verificationDateExpired
        );
    }
}