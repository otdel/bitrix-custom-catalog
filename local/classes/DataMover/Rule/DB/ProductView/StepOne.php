<?php


namespace Oip\DataMover\Rule\DB\ProductView;

use Oip\DataMover\Rule\RuleStepInterface;

class StepOne implements RuleStepInterface
{
    /** @inheritDoc */
    public static function getStep(): callable
    {
        return function(int $productId, int $sectionId, int $userId) {
            return "SELECT `views_count` FROM `oip_product_view` WHERE `product_id` = $productId AND"
            ." `section_id` = $sectionId AND `user_id` = $userId";
        };
    }
}