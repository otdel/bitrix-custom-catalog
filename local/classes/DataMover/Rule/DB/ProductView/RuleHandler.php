<?php

namespace Oip\DataMover\Rule\DB\ProductView;

use Oip\DataMover\Repository\RepositoryInterface;
use Oip\DataMover\Rule\Rule;
use Oip\DataMover\Rule\RuleHandlerInterface;

class RuleHandler implements RuleHandlerInterface
{
    /** @var RepositoryInterface $repository */
    private $repository;

    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function handleRule(Rule $rule, array $uniqueSet, int $guestId, int $userId) {
        $stepOneAction = $rule->getStep("Oip\DataMover\Rule\DB\ProductView\StepOne");
        $userViewsCount = $this->repository
            ->executeQuery($stepOneAction($uniqueSet["product_id"], $uniqueSet["section_id"], $userId))->fetch()["views_count"];

        $guestViewsCount = $this->repository
            ->executeQuery($stepOneAction($uniqueSet["product_id"], $uniqueSet["section_id"], $guestId))->fetch()["views_count"];

        $stepTwoAction = $rule->getStep("Oip\DataMover\Rule\DB\ProductView\StepTwo");
        $userIsLiked = $this->repository
            ->executeQuery($stepTwoAction($uniqueSet["product_id"], $uniqueSet["section_id"], $userId))->fetch()["likes_count"];

        $guestIsLiked = $this->repository
            ->executeQuery($stepTwoAction($uniqueSet["product_id"], $uniqueSet["section_id"], $guestId))->fetch()["likes_count"];

        $stepThreeAction = $rule->getStep("Oip\DataMover\Rule\DB\ProductView\StepThree");
        $viewsSum = $stepThreeAction((int)$guestViewsCount, (int)$userViewsCount);

        $stepFourAction = $rule->getStep("Oip\DataMover\Rule\DB\ProductView\StepFour");
        $isLikedSum = $stepFourAction((int)$guestIsLiked, (int)$userIsLiked);

        $stepFiveAction = $rule->getStep("Oip\DataMover\Rule\DB\ProductView\StepFive");
        $this->repository->executeQuery($stepFiveAction($uniqueSet["product_id"], $uniqueSet["section_id"], $userId,
            $viewsSum, $isLikedSum));

        $stepSixAction = $rule->getStep("Oip\DataMover\Rule\DB\ProductView\StepSix");
        $this->repository->executeQuery($stepSixAction($uniqueSet["product_id"], $uniqueSet["section_id"], $guestId));
    }
}