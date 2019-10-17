<?php

namespace Oip\Custom\Component\Iblock;

class Element
{

    /** @var array $data */
    private $data;

    private $id;
    private $code;
    private $iblockId;
    private $sectionId;
    private $sort;
    private $name;
    private $active;
    private $activeFrom;
    private $activeTo;
    private $listUrl;
    private $sectionUrl;
    private $detailUrl;
    private $previewPicture;
    private $detailPicture;
    private $previewText;
    private $detailText;

    public function __construct(&$data)
    {

        $this->id = $data["FIELDS"]["ID"];
        $this->code = $data["FIELDS"]["CODE"];
        $this->iblockId = $data["FIELDS"]["IBLOCK_ID"];
        $this->sectionId = $data["FIELDS"]["SECTION_ID"];
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

        $this->props = $data["PROPS"];
    }

    /** @return array */
    private function getFields(){
        return $this->data["FIELDS"];
    }

    /** @return array */
    private function getProps() {
        return $this->props;
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

    /** @return array */
    public function getProp($propCode) {
        return $this->getProps()[$propCode];
    }

    /**
     * @param string $propCode
     * @return mixed
     */
    public function getPropValue($propCode) {
        return $this->getProps()[$propCode]["VALUE"];
    }

    public function getPropName($propCode) {
        return $this->getProps()[$propCode]["NAME"];
    }
}