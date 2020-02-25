<?php

namespace Oip\Util\Bitrix\Iblock\ElementPath;

use Bitrix\Main\DB\Connection;
use Bitrix\Main\DB\SqlQueryException;

class Helper
{
    /** @var Connection $db */
    private $db;
    /** @var int $iblockId */
    private $iblockId;
    /** @var string $baseDir */
    private $baseDir;

    public function __construct(Connection $db, int $iblockId) {
        $this->db = $db;
        $this->iblockId = $iblockId;
    }

    /** @throws SqlQueryException */
    public function makeUrl(int $elementId, ?string $baseDirCustom = null): string {
        $baseDir = ($baseDirCustom) ?? $this->getBaseDir();
        if(!$baseDir) {
            $baseDir = $this->iblockId;
        }
        $url = $this->normalizeBaseDir($baseDir);

        if($url) {
            $element = $this->fetchElement($elementId);
            if($element["IBLOCK_SECTION_ID"]) {
                $url .= $this->makeSectionPath((int)$element["IBLOCK_SECTION_ID"]);
            }
            if($element) {
                $url .= ($element["CODE"]) ? "/" . $element["CODE"] . "/" : "/" . $elementId . "/";
            }
            else {
                $url .= "/";
            }
        }


        return $url;
    }

    /** @throws SqlQueryException */
    public function makeSectionPath(int $sectionId): string {
        $path = "";

        $section = $this->fetchSection($sectionId);

        if(!empty($section)) {
            $path = ($section["CODE"]) ? "/" . $section["CODE"] : "/" . $sectionId;

            if($section["IBLOCK_SECTION_ID"]) {
                $path = $this->makeSectionPath((int)$section["IBLOCK_SECTION_ID"]) . $path;
            }
        }

        return $path;
    }

    /** @throws SqlQueryException */
    public function getBaseDir(): string {
        if(is_null($this->baseDir)) {
            $this->baseDir = $this->fetchBaseDir($this->iblockId);
        }

        return $this->baseDir;
    }

    public function normalizeBaseDir(string $baseDir): string {
        if(substr($baseDir, 0,1) !== "/") {
            $baseDir = "/" . $baseDir;
        }

        if(substr($baseDir, -1) === "/") {
            $baseDir = substr($baseDir, 0, -1);
        }

        return $baseDir;
    }

    /** @throws SqlQueryException */
    private function fetchElement(int $id): array {
        $res = $this->db->query("SELECT CODE, IBLOCK_SECTION_ID FROM b_iblock_element WHERE ID = $id")
            ->fetch();
        return ($res) ? $res : [];
    }

    /** @throws SqlQueryException */
    private function fetchSection(int $id): array {
        $res = $this->db->query("SELECT CODE, IBLOCK_SECTION_ID FROM b_iblock_section WHERE ID = $id")
            ->fetch();
        return ($res) ? $res : [];
    }

    /** @throws SqlQueryException */
    private function fetchBaseDir(int $id): string {
        return (string)$this->db->query("SELECT LIST_PAGE_URL FROM b_iblock WHERE ID = $id")
            ->fetch()["LIST_PAGE_URL"];
    }
}