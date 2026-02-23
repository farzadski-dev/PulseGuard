<?php

declare(strict_types=1);

namespace App\Domain\Pipelines\Steps;

use App\Domain\Entities\RiskResult;
use App\Domain\Pipelines\PipelineStep;
use App\Domain\Pipelines\RiskContext;
use App\Domain\ValueObjects\RiskScore;

final class AggregationStep implements PipelineStep
{
    public function process(RiskContext $context): RiskContext
    {
        $strategyScores = $context->strategyScores();
        $ruleResult = $context->ruleResult();

        $baseScore = array_sum($strategyScores);
        $adjustment = $ruleResult?->totalAdjustment() ?? 0;
        $final = max(0, min(1000, $baseScore + $adjustment));

        $score = new RiskScore($final);
        $level = $score->level();

        $context->setFinalScore($final);
        $context->setRiskLevel($level);

        $context->setResult(new RiskResult(
            $context->transaction()->transactionId(),
            $context->transaction()->tenantId(),
            $score,
            $level,
            $strategyScores,
            $ruleResult?->adjustments() ?? [],
            $context->reasons(),
            new \DateTimeImmutable('now')
        ));

        return $context;
    }
}
