<?php

namespace Oip\GuestUser\Clearer\Entity\ProductView;

use DateTime;

class Record
{
    /** @var int $id */
    private $id;
    /** @var int $userId */
    private $userId;
    /** @var int|null $productId */
    private $productId;
    /** @var int|null $sectionId */
    private $sectionId;
    /** @var DateTime $dateInsert */
    private $dateInsert;
    /** @var DateTime $dateModify */
    private $dateModify;
    /** @var int $viewsCount */
    private $viewsCount;
    /** @var int $likesCount */
    private $likesCount;

    /**
     * @param int $id
     * @param int $userId
     * @param int|null $productId
     * @param int|null $sectionId
     * @param DateTime $dateInsert
     * @param DateTime $dateModify
     * @param int $viewsCount
     * @param int $likesCount
     */
    public function __construct(int $id, int $userId, ?int $productId, ?int $sectionId,
                                DateTime $dateInsert, DateTime $dateModify, int $viewsCount, int $likesCount)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->productId = $productId;
        $this->sectionId = $sectionId;
        $this->dateInsert = $dateInsert;
        $this->dateModify = $dateModify;
        $this->viewsCount = $viewsCount;
        $this->likesCount = $likesCount;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @return int|null
     */
    public function getProductId(): ?int
    {
        return $this->productId;
    }

    /**
     * @return int|null
     */
    public function getSectionId(): ?int
    {
        return $this->sectionId;
    }

    /**
     * @return DateTime
     */
    public function getDateInsert(): DateTime
    {
        return $this->dateInsert;
    }

    /**
     * @return DateTime
     */
    public function getDateModify(): DateTime
    {
        return $this->dateModify;
    }

    /**
     * @return int
     */
    public function getViewsCount(): int
    {
        return $this->viewsCount;
    }

    /**
     * @return int
     */
    public function getLikesCount(): int
    {
        return $this->likesCount;
    }


}