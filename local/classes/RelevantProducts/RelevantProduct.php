<?php

namespace Oip\RelevantProducts;

use Oip\RelevantProducts\Config\Configuration;

class RelevantProduct
{
    /** @var int  */
    private $id;
    /** @var int */
    private $viewsCount;
    /** @var int */
    private $likesCount;
    /** @var int */
    private $iBlockId;
    /** @var int */
    private $iBlockSectionId;
    /** @var \DateTime */
    private $dateFirstView;
    /** @var \DateTime */
    private $dateLastView;

    /**
     * @param int $id
     * @param int $iBlockId
     * @param $iBlockSectionId
     */
    public function __construct(
        $id, $iBlockId, $iBlockSectionId
    ) {
        $this->id = $id;
        $this->iBlockId = $iBlockId;
        $this->iBlockSectionId = $iBlockSectionId;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getViewsCount()
    {
        return $this->viewsCount;
    }

    /**
     * @return int
     */
    public function getLikesCount()
    {
        return $this->likesCount;
    }

    /**
     * @return int
     */
    public function getIBlockSectionId()
    {
        return $this->iBlockSectionId;
    }

    /**
     * @return \DateTime
     */
    public function getDateFirstView()
    {
        return $this->dateFirstView;
    }

    /**
     * @return \DateTime
     */
    public function getDateLastView()
    {
        return $this->dateLastView;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param int $count
     */
    public function setViewsCount($count)
    {
        $this->viewsCount = $count;
    }

    /**
     * @param int $count
     */
    public function setLikesCount($count)
    {
        $this->likesCount = $count;
    }

    /**
     * @return int
     */
    public function getIBlockId(): int
    {
        return $this->iBlockId;
    }

    /**
     * @param int $iBlockId
     */
    public function setIBlockId(int $iBlockId): void
    {
        $this->iBlockId = $iBlockId;
    }

    /**
     * @param int $iBlockSectionId
     */
    public function setIBlockSectionId($iBlockSectionId)
    {
        $this->iBlockId = $iBlockSectionId;
    }

    /**
     * @param \DateTime $dateFirstView
     */
    public function setDateFirstView($dateFirstView)
    {
        $this->dateFirstView = $dateFirstView;
    }

    /**
     * @param \DateTime $dateLastView
     */
    public function setDateLastView($dateLastView)
    {
        $this->dateLastView = $dateLastView;
    }

    /**
     * @return int
     */
    public function getWeight() {
        return
            $this->getViewsCount() * Configuration::PRODUCT_VIEW_WEIGHT +
            $this->getLikesCount() * Configuration::PRODUCT_LIKE_WEIGHT;
    }

    /**
     * Получение идентификатора раздела. Если это корневой (верхний) раздел - вернет ID инфоблока
     *
     * @return int
     */
    public function getSectionId()
    {
        return isset($this->iBlockSectionId) ? $this->iBlockSectionId : $this->iBlockId;
    }


}
