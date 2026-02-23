<?php

declare(strict_types=1);

namespace App\Domain\Pipelines\Steps;

use App\Domain\Pipelines\PipelineStep;
use App\Domain\Pipelines\RiskContext;
use App\Domain\Strategies\RiskScoringStrategy;

final class StrategyScoringStep implements PipelineStep
{
    /** @param iterable<RiskScoringStrategy> $strategies */
    public function __construct(private iterable $strategies)
    {
    }

    public function process(RiskContext $context): RiskContext
    {
        foreach ($this->strategies as $strategy) {
            $score = $strategy->score($context->transaction(), $context);
            $context->addStrategyScore($strategy->name(), $score);

            foreach ($strategy->reasons($context->transaction(), $context) as $reason) {
                $context->addReason($reason);
            }
        }

        return $context;
    }
}
