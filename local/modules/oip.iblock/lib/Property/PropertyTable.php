<?php

namespace Oip\Iblock\Property;

use Bitrix\Iblock\PropertyTable as BitrixPropertyTable;
use Bitrix\Main\ORM\Fields\Relations\OneToMany;

use Oip\Iblock\PropertyValue\PropertyValueTable;

class PropertyTable extends BitrixPropertyTable
{
    public static function getObjectClass()
    {
        return Property::class;
    }

    public static function getCollectionClass()
    {
        return PropertyCollection::class;
    }

    public static function getMap() {
        $map = parent::getMap();

        $map[] = (new OneToMany(
            "VALUES",
            PropertyValueTable::class,
            "PROPERTY"
            ))->configureJoinType("inner");

        return $map;
    }
}