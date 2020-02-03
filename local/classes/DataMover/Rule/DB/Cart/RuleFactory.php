<?php


namespace Oip\DataMover\Rule\DB\Cart;

use Oip\DataMover\Rule\Rule;
use Oip\DataMover\Rule\RuleFactoryInterface;


class RuleFactory implements RuleFactoryInterface
{
    public static function buildRule(): Rule {

        $steps = [
            StepOne::class => StepOne::getStep(),
            StepTwo::class => StepTwo::getStep(),
        ];

        return new Rule($steps);
    }
}