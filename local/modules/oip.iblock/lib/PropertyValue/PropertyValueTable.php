<?php

namespace Oip\Iblock\PropertyValue;

use Bitrix\Main\Entity;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;

use Oip\Iblock\Property\PropertyTable;
use Oip\Iblock\Element\ElementTable;

class PropertyValueTable extends Entity\DataManager
{

    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName(){
        return 'b_iblock_element_property';
    }

    /**
     * Returns entity map definition.
     *
     * @return array
     */
    public static function getMap()
    {
        return array(
            'ID' => new Entity\IntegerField('ID', array(
                'primary' => true,
                'autocomplete' => true,
                'title' => "ID записи",
            )),
            'IBLOCK_PROPERTY_ID' => new Entity\IntegerField('IBLOCK_PROPERTY_ID', array(
                'title' => "ID свойства",
            )),
            (new Reference(
                "PROPERTY",
                PropertyTable::class,
                Join::on('this.IBLOCK_PROPERTY_ID', 'ref.ID')
            ))->configureJoinType('inner'),

            'IBLOCK_ELEMENT_ID' => new Entity\IntegerField('IBLOCK_ELEMENT_ID', array(
                'title' => "ID элемента инфоблока",
            )),
            (new Reference(
                "ELEMENT",
                ElementTable::class,
                Join::on('this.IBLOCK_ELEMENT_ID', 'ref.ID')
            ))->configureJoinType('inner'),

            'VALUE' => new Entity\StringField('VALUE', array(
                'title' => "Значение",
            )),
            'VALUE_TYPE' => new Entity\StringField('VALUE_TYPE', array(
                'title' => "Тип значения",
            )),
            'VALUE_ENUM' => new Entity\StringField('VALUE_ENUM', array(
                'title' => "ID элемента списка в свойстве типа Список",
            )),
            'VALUE_NUM' => new Entity\StringField('VALUE_NUM', array(
                'title' => "VALUE_NUM",
            )),
            'DESCRIPTION' => new Entity\TextField('DESCRIPTION', array(
                'title' => "Описание",
            )),

        );
    }

    public static function getObjectClass()
    {
        return PropertyValue::class;
    }

    public static function getCollectionClass()
    {
        return PropertyValueCollection::class;
    }

}