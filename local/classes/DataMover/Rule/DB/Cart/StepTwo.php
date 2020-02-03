<?php


namespace Oip\DataMover\Rule\DB\Cart;

use Oip\DataMover\Rule\RuleStepInterface;

class StepTwo implements RuleStepInterface
{
    /** @inheritDoc */
    public static function getStep(): callable
    {
        return function(int $productId, int $guestId) {
            return "DELETE FROM `oip_carts` WHERE  product_id = $productId AND user_id = $guestId";
        };
    }
}