<?php


namespace Oip\DataMover\Rule;

interface RuleHandlerInterface
{
    public function handleRule(Rule $rule, array $uniqueSet, int $guestId, int $userId);
}