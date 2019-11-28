<?php

namespace Oip\RelevantProducts;

class CompareFunctions
{
    /**
     * @param RelevantSection $a
     * @param RelevantSection $b
     * @return int
     */
    public static function compareCategoriesByWeight($a, $b)
    {
        return $b->getWeight() - $a->getWeight();
    }

    /**
     * @param RelevantSection $a
     * @param RelevantSection $b
     * @return int
     */
    public static function compareCategoriesByViews($a, $b)
    {
        return $b->getViewsCount() - $a->getViewsCount();
    }

    /**
     * @param RelevantSection $a
     * @param RelevantSection $b
     * @return int
     */
    public static function compareCategoriesByLikes($a, $b)
    {
        return $b->getLikesCount() - $a->getLikesCount();
    }

    /**
     * @param RelevantProduct $a
     * @param RelevantProduct $b
     * @return int
     */
    public static function compareProductsByViews($a, $b)
    {
        return $b->getViewsCount() - $a->getViewsCount();
    }

    /**
     * @param RelevantProduct $a
     * @param RelevantProduct $b
     * @return int
     */
    public static function compareProductsByLikes($a, $b)
    {
        return $b->getLikesCount() - $a->getLikesCount();
    }

}
