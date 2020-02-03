<?php


namespace Oip\DataMover\Rule\DB\ProductView;


class StepFive
{
    /** @inheritDoc */
    public static function getStep(): callable
    {
        return function(int $productId, int $sectionId, int $userId, int $viewsCount, int $isLiked) {
            return "UPDATE `oip_product_view` set date_modify = NOW(), views_count = $viewsCount, is_liked = $isLiked"
                ." WHERE product_id = $productId AND section_id = $sectionId AND  user_id = $userId";
        };
    }
}