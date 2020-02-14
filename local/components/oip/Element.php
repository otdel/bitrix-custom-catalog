<?php

namespace Oip\Custom\Component\Iblock;

class Element
{

    /** @var int $id */
    private $id;
    /** @var string $code */
    private $code;
    /** @var int $iblockId */
    private $iblockId;
    /** @var int $sectionId */
    private $sectionId;
    /** @var int $sectionName */
    private $sectionName;
    /** @var int $sort */
    private $sort;
    /** @var string $name */
    private $name;
    /** @var string $active */
    private $active;
    /** @var string $activeFrom */
    private $activeFrom;
    /** @var string $activeFrom */
    private $activeTo;
    /** @var string $listUrl */
    private $listUrl;
    /** @var string $sectionUrl */
    private $sectionUrl;
    /** @var string $detailUrl */
    private $detailUrl;
    /** @var string $previewPicture */
    private $previewPicture;
    /** @var string $detailPicture */
    private $detailPicture;
    /** @var string $previewText */
    private $previewText;
    /** @var string $detailText */
    private $detailText;
    /** @var Property[] $props */
    private $props;

    public function __construct($data)
    {

        $this->id = $data["FIELDS"]["ID"];
        $this->code = $data["FIELDS"]["CODE"];
        $this->iblockId = $data["FIELDS"]["IBLOCK_ID"];
        $this->sectionId = $data["FIELDS"]["IBLOCK_SECTION_ID"];
        $this->sectionName = $data["FIELDS"]["SECTION_NAME"];
        $this->sort = $data["FIELDS"]["SORT"];
        $this->name = $data["FIELDS"]["NAME"];
        $this->active = $data["FIELDS"]["ACTIVE"];
        $this->activeFrom = $data["FIELDS"]["ACTIVE_FROM"];
        $this->activeTo = $data["FIELDS"]["ACTIVE_TO"];
        $this->listUrl = $data["FIELDS"]["LIST_PAGE_URL"];
        $this->sectionUrl = $data["FIELDS"]["SECTION_PAGE_URL"];
        $this->detailUrl = $data["FIELDS"]["DETAIL_PAGE_URL"];
        $this->previewPicture = $data["FIELDS"]["PREVIEW_PICTURE"];
        $this->detailPicture = $data["FIELDS"]["DETAIL_PICTURE"];
        $this->previewText = $data["FIELDS"]["PREVIEW_TEXT"];
        $this->detailText = $data["FIELDS"]["DETAIL_TEXT"];


        $props = [];
        foreach($data["PROPS"] as $propCode => $arProp) {
            $props[$propCode] = new Property($arProp);
        }

        $this->props = $props;
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
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return int
     */
    public function getIblockId()
    {
        return $this->iblockId;
    }

    /**
     * @return int
     */
    public function getSectionId()
    {
        return $this->sectionId;
    }
    
    /**
     * @return string
     */
    public function getSectionName()
    {
        return $this->sectionName;
    }

    /**
     * @return int
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @return boolean
     */
    public function isActive()
    {
        return ($this->getActive() === "Y");
    }

    /**
     * @return string
     */
    public function getActiveFrom()
    {
        return $this->activeFrom;
    }

    /**
     * @return string
     */
    public function getActiveTo()
    {
        return $this->activeTo;
    }


    /**
     * @return string
     */
    public function getListUrl()
    {
        return $this->listUrl;
    }

    /**
     * @return string
     */
    public function getSectionUrl()
    {
        return $this->sectionUrl;
    }

    /**
     * @return string
     */
    public function getDetailUrl()
    {
        return $this->detailUrl;
    }

    /**
     * @return string
     */
    public function getPreviewPicture()
    {
        return $this->getPicture($this->previewPicture);
    }

    /**
     * @return string
     */
    public function getPreviewPictureDescription()
    {
        return $this->previewPicture["DESCRIPTION"];
    }

    /**
     * @return string
     */
    public function getDetailPicture()
    {
        return $this->getPicture($this->detailPicture);
    }

    /**
     * @return string
     */
    public function getDetailPictureDescription()
    {
        return $this->detailPicture["DESCRIPTION"];
    }

    /**
     * @return string
     */
    private function getPicture(&$picture) {
        return ($picture) ? "/upload/".$picture["SUBDIR"]."/".$picture["FILE_NAME"] : "";
    }

    /**
     * @return string
     */
    public function getPreviewText() {
        return $this->previewText;
    }

    /**
     * @return string
     */
    public function getDetailText() {
        return $this->detailText;
    }

    /** @return Property[] */
    public function getProps() {
        return $this->props;
    }

    /** @return Property|null */
    public function getProp($propCode) {
        return $this->getProps()[$propCode];
    }

    /**
     * @param string $propCode
     * @return mixed
     */
    public function getPropValue($propCode) {
        return ($this->getProps()[$propCode]) ? $this->getProps()[$propCode]->getValue() : "";
    }

    /**
     * @param string $propCode
     * @param int|null $key
     * @return string
     */
    public function getPropValueFromMultiple($propCode, $key = 0) {
        return ($this->getProps()[$propCode]) ? $this->getProps()[$propCode]->getValueFromMultiple($key) : "";
    }

    /**
     * @param string $propCode
     * @return string
     */
    public function getPropValueDescription($propCode) {
        return ($this->getProps()[$propCode]) ? $this->getProps()[$propCode]->getDescription() : "";
    }

    /**
     * @param string $propCode
     * @param int $key
     * @return string
     */
    public function getPropValueDescriptionFromMultiple($propCode, $key = 0) {
        return ($this->getProps()[$propCode]) ? $this->getProps()[$propCode]->getDescriptionFromMultiple($key) : "";
    }

    /**
     * @return int
     */
    public function getPropValueCount($propCode) {
        return  ($this->getProps()[$propCode]) ? $this->getProps()[$propCode]->getValueCount() : 0;
    }

    /**
     * @param string $propCode
     * @return string
     */
    public function getPropName($propCode) {
        return  ($this->getProps()[$propCode]) ? $this->getProps()[$propCode]->getName() : "";
    }
}