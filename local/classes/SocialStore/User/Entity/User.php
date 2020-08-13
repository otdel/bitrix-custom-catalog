<?php

namespace Oip\SocialStore\User\Entity;

use DateTimeImmutable;
use DomainException;
use Oip\SocialStore\User\Entity\Exception\IncorrectVerificationProcessException;
use Oip\SocialStore\User\Entity\Exception\VerificationCodeExpiredException;
use Oip\SocialStore\User\Entity\Exception\VerificationFailedException;
use Oip\SocialStore\User\Entity\Exception\AlreadyVerifiedException;

class User
{
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
        int $id,
        string $email,
        string $phone,
        int $bxId,
        int $phoneVerified,
        string $name,
        ?string $surname = null,
        ?string $patronymic = null,
        ?string $verificationCode = null,
        ?DateTimeImmutable $verificationDateExpired = null
    )
    {
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

    /**
     * @return string
     * @throws DomainException
     */
    public function generateVerificationCode(): string {
        if($this->phoneVerified) {
            throw new AlreadyVerifiedException("Номер телефона уже верифицирован.");
        }

        return str_pad(rand(0,999999), 6, "0", STR_PAD_LEFT);
    }

    /**
     * @param string $verificationCode
     * @param DateTimeImmutable $verifyingDate
     * @throws IncorrectVerificationProcessException
     * @throws VerificationCodeExpiredException
     * @throws VerificationFailedException
     */
    public function checkVerification(string $verificationCode, DateTimeImmutable $verifyingDate) {

        if(!$this->verificationCode || !$this->verificationDateExpired) {
            throw new IncorrectVerificationProcessException("Процедура верификации не была корректно запущена.");
        }

        if($this->isVerificationCodeExpired($verifyingDate)) {
            throw new VerificationCodeExpiredException("Срок активности текущего кода верификации истек.");
        }

        if($verificationCode !== $this->verificationCode) throw new VerificationFailedException("Неверный код верификации.");
    }

    /**
     * @param DateTimeImmutable $verifyingDate
     * @return bool
     */
    public function isVerificationCodeExpired(DateTimeImmutable $verifyingDate) {
        if(!$this->verificationDateExpired) {
            throw new IncorrectVerificationProcessException("Процедура верификации не была корректно запущена");
        }

        return ($verifyingDate->getTimestamp() >= $this->verificationDateExpired->getTimestamp());
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getSurname(): ?string
    {
        return $this->surname;
    }

    /**
     * @return string|null
     */
    public function getPatronymic(): ?string
    {
        return $this->patronymic;
    }

    /**
     * @return string
     */
    public function getFIO(): string {

        if(!$this->name) {
            return "";
        }

        $fio = $this->name;

        if($this->surname) {
            $fio = $this->surname . " " . $fio;
        }

        if($this->patronymic) {
            $fio = $fio . " " . $this->patronymic;
        }

        return $fio;
    }

    /**
     * @return int
     */
    public function getBxId(): int
    {
        return $this->bxId;
    }

    /** @return bool */
    public function isPhoneVerified(): bool {
        return (bool)$this->phoneVerified;
    }

    /** @return string|null */
    public function getVerificationCode(): ?string {
        return $this->verificationCode;
    }

    /** @return DateTimeImmutable|null */
    public function getVerificationDateExpired(): ?DateTimeImmutable {
        return $this->verificationDateExpired;
    }
}