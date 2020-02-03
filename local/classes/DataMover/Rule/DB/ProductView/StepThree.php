<?php


namespace Oip\DataMover\Rule\DB\ProductView;

use Oip\DataMover\Rule\RuleStepInterface;

class StepThree  implements RuleStepInterface
{
    /** @return callable */
    public static function getStep(): callable
    {
        return function(int $guestViewsCount, int $userViewsCount) {
            return  $guestViewsCount + $userViewsCount;
        };
    }
}