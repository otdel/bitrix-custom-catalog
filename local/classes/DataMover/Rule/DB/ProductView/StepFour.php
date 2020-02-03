<?php


namespace Oip\DataMover\Rule\DB\ProductView;

use Oip\DataMover\Rule\RuleStepInterface;

class StepFour implements RuleStepInterface
{
    /** @return callable */
    public static function getStep(): callable
    {
        return function(int $guestIsLiked, int $userIsLiked) {
            return  (int)($guestIsLiked || $userIsLiked);
        };
    }
}