<?php

namespace Oip\Iblock\Element;

use Bitrix\Iblock\ElementTable as BitrixElementTable;
use Bitrix\Main\ORM\Fields\Relations\OneToMany;

use Oip\Iblock\PropertyValue\PropertyValueTable;

class ElementTable extends BitrixElementTable
{
    public static function getObjectClass()
    {
        return Element::class;
    }

    public static function getCollectionClass()
    {
        return ElementCollection::class;
    }

    public static function getMap() {
        $map = parent::getMap();

        $map[] = (new OneToMany(
            "PROPERTY_VALUES",
            PropertyValueTable::class,
            "ELEMENT"
        ))->configureJoinType("inner");

        return $map;
    }
}