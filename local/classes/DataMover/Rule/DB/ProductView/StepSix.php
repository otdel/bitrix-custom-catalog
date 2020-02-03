<?php


namespace Oip\DataMover\Rule\DB\ProductView;


class StepSix
{
    /** @inheritDoc */
    public static function getStep(): callable
    {
        return function(int $productId, int $sectionId, int $guestId) {
            return "DELETE FROM `oip_product_view` WHERE product_id = $productId AND section_id = $sectionId "
                ." AND user_id = $guestId";
        };
    }
}