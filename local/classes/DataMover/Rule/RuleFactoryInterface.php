<?php


namespace Oip\DataMover\Rule;

interface RuleFactoryInterface
{
    public static function buildRule(): Rule;
}