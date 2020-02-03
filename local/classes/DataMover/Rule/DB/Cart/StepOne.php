<?php


namespace Oip\DataMover\Rule\DB\Cart;

use Oip\DataMover\Rule\RuleStepInterface;

class StepOne implements RuleStepInterface
{
    /** @inheritDoc */
    public static function getStep(): callable
    {
        return function(int $productId, int $userId) {
            return "UPDATE `oip_carts` set date_modify = NOW() WHERE product_id = $productId AND user_id = $userId";
        };
    }
}