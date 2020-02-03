<?php


namespace Oip\DataMover\Rule;

interface RuleStepInterface
{
    /** @return callable */
    public static function getStep(): callable;
}