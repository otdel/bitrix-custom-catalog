<?php

namespace Oip\Iblock;

use Bitrix\Main\Entity;

class ElementPropertyTable extends Entity\DataManager
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
            'IBLOCK_ELEMENT_ID' => new Entity\IntegerField('IBLOCK_ELEMENT_ID', array(
                'title' => "ID элемента инфоблока",
            )),
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

}