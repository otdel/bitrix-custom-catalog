<?php


namespace Oip\DataMover\Rule\DB\ProductView;

use Oip\DataMover\Rule\Rule;
use Oip\DataMover\Rule\RuleFactoryInterface;

class RuleFactory implements RuleFactoryInterface
{
    public static function buildRule(): Rule {

        $steps = [
            StepOne::class => StepOne::getStep(),
            StepTwo::class => StepTwo::getStep(),
            StepThree::class => StepThree::getStep(),
            StepFour::class => StepFour::getStep(),
            StepFive::class => StepFive::getStep(),
            StepSix::class => StepSix::getStep(),
        ];

        return new Rule($steps);
    }
}