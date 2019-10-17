<?php

namespace Oip\Custom\Component\Iblock;

class Element
{

    /** @var array $data */
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /** @return array */
    private function getFields(){
        return $this->data["FIELDS"];
    }

    /** @return array */
    private function getProps() {
        return $this->data["PROPS"];
    }

    /** @return array */
    public function getRawData() {
        return $this->data;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->getFields()["ID"];
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->getFields()["CODE"];
    }

    /**
     * @return int
     */
    public function getIblockId()
    {
        return $this->getFields()["IBLOCK_ID"];
    }

    /**
     * @return int
     */
    public function getSectionId()
    {
        return $this->getFields()["IBLOCK_SECTION_ID"];
    }

    /**
     * @return int
     */
    public function getSort()
    {
        return $this->getFields()["SORT"];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->getFields()["NAME"];
    }

    /**
     * @return string
     */
    public function getActive()
    {
        return $this->getFields()["ACTIVE"];
    }

    /**
     * @return boolean
     */
    public function isActive()
    {
        return ($this->getFields()["ACTIVE"] === "Y");
    }

    /**
     * @return string
     */
    public function getActiveFrom()
    {
        return $this->getFields()["ACTIVE_FROM"];
    }

    /**
     * @return string
     */
    public function getActiveTo()
    {
        return $this->getFields()["ACTIVE_TO"];
    }


    /**
     * @return string
     */
    public function getListUrl()
    {
        return $this->getFields()["LIST_PAGE_URL"];
    }

    /**
     * @return string
     */
    public function getSectionUrl()
    {
        return $this->getFields()["SECTION_PAGE_URL"];
    }

    /**
     * @return string
     */
    public function getDetailUrl()
    {
        return $this->getFields()["DETAIL_PAGE_URL"];
    }

    /**
     * @return string
     */
    public function getPreviewPicture()
    {
        return $this->getPicture("PREVIEW_PICTURE");
    }

    /**
     * @return string
     */
    public function getPreviewPictureDescription()
    {
        return $this->getPictureDescription("PREVIEW_PICTURE");
    }

    /**
     * @return string
     */
    public function getDetailPicture()
    {
        return $this->getPicture("DETAIL_PICTURE");
    }

    /**
     * @return string
     */
    public function getDetailPictureDescription()
    {
        return $this->getPictureDescription("DETAIL_PICTURE");
    }

    /**
     * @return string
     */
    private function getPicture($pictureType) {
        $pic = $this->getFields()[$pictureType];
        return ($pic) ? "/upload/".$pic["SUBDIR"]."/".$pic["FILE_NAME"] : "";
    }

    /**
     * @return string
     */
    private function getPictureDescription($pictureType) {
        return $this->getFields()[$pictureType]["DESCRIPTION"];
    }

    /**
     * @return string
     */
    public function getPreviewText() {
        return $this->getText("PREVIEW_TEXT");
    }

    /**
     * @return string
     */
    public function getDetailText() {
        return $this->getText("DETAIL_TEXT");
    }

    /**
     * @param string $textType
     * @return string
     */
    private function getText($textType) {
        return $this->getFields()[$textType];
    }

    /**
     * @param string $fieldName
     * @return string
     */
    public function getField($fieldName) {
        return $this->getFields()[$fieldName];
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