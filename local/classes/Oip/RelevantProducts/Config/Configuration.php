<?php

namespace Oip\RelevantProducts\Config;

/**
 * Class Configuration
 * Общие настройки компонента релевантных товаров
 *
 * @package Oip\RelevantProducts\Config
 */
final class Configuration {
    /** "Вес" просмотра товара */
    const PRODUCT_VIEW_WEIGHT = 1;
    /** "Вес" лайка на товаре */
    const PRODUCT_LIKE_WEIGHT = 5;
    /** Сколько минут после добавления товара считать его новым */
    const NEW_PRODUCT_LIFETIME = 4320; // 4320‬ - трое суток
    /** Сколько минут должно пройти перед тем, как можно будет инкрементировать кол-во просмотров товара */
    const ADD_PRODUCT_VIEW_INTERVAL = 5;
    /** Сколько минут должно пройти перед тем, как можно будет инкрементировать кол-во просмотров раздела */
    const ADD_SECTION_VIEW_INTERVAL = 10;
}