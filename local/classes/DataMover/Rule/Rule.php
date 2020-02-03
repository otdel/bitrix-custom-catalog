<?php

namespace Oip\DataMover\Rule;

use Oip\Util\Collection\Collection;

class Rule extends Collection
{
    /** @var RuleStepInterface[] $values */

    /**
     * @param RuleStepInterface[] $steps
     */
    public function __construct(array $steps)
    {
        $this->values = $steps;
    }

    /**
     * @return RuleStepInterface[]
     */
    public function getSteps(): array
    {
        return $this->values;
    }

    /**
     * @param string $key
     * @return callable
     */
    public function getStep(string $key): callable {
        return $this->values[$key];
    }
}