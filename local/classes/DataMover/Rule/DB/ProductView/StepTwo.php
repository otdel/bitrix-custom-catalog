<?php


namespace Oip\DataMover\Rule\DB\ProductView;

use Oip\DataMover\Rule\RuleStepInterface;

class StepTwo implements RuleStepInterface
{
    /** @return callable */
    public static function getStep(): callable
    {
        return function(int $productId, int $sectionId, int $userId) {
            return "SELECT `is_liked` FROM `oip_product_view` WHERE `product_id` = $productId AND"
            ." `section_id` = $sectionId AND `user_id` = $userId";
        };
    }
}