<?php

namespace Oip\SocialStore\Order\Status\Entity;


class Status
{
    const STATUS_CREATED = "created";
    const STATUS_TRANSFERRED = "transferred";
    const START_STATUS_CODE = self::STATUS_CREATED;


    /** @var int */
    private $id;
    /** @var string */
    private $code;
    /** @var string */
    private $label;

    /**
     * @param int $id
     * @param string $code
     * @param string $label
     */
    public function __construct(int $id, string $code, string $label)
    {
        $this->id = $id;
        $this->code = $code;
        $this->label = $label;
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
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }


}