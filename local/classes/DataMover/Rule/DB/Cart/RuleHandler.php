<?php

namespace Oip\DataMover\Rule\DB\Cart;

use Oip\DataMover\Rule\Rule;
use Oip\DataMover\Rule\RuleHandlerInterface;

use Oip\DataMover\Repository\RepositoryInterface;

class RuleHandler implements RuleHandlerInterface
{
    /** @var RepositoryInterface $repository */
    private $repository;

    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function handleRule(Rule $rule, array $uniqueSet, int $guestId, int $userId)
    {
        $stepOneAction = $rule->getStep("Oip\DataMover\Rule\DB\Cart\StepOne");
        $this->repository->executeQuery($stepOneAction($uniqueSet["product_id"], $userId));

        $stepTwoAction = $rule->getStep("Oip\DataMover\Rule\DB\Cart\StepTwo");
        $this->repository->executeQuery($stepTwoAction($uniqueSet["product_id"], $guestId));
    }
}