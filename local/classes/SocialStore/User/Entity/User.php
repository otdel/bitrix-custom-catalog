<?php

namespace Oip\SocialStore\User\Entity;

class User
{
    /** @var int $id */
    private $id;

    /** @var string|null $email */
    private $email;

    /** @var string|null $phone */
    private $phone;

    /** @var string|null $name */
    private $name;

    /** @var string|null $surname */
    private $surname;

    /** @var string|null $patronymic */
    private $patronymic;

    /* @var int $bxId */
    private $bxId;

    public function __construct(
        int $id,
        ?string $email = null,
        ?string $phone = null,
        ?int $bxId = null,
        ?string $name = null,
        ?string $surname = null,
        ?string $patronymic = null
    )
    {
        $this->id = $id;
        $this->email = $email;
        $this->phone = $phone;
        $this->bxId = $bxId;
        $this->name = $name;
        $this->surname = $surname;
        $this->patronymic = $patronymic;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
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
}