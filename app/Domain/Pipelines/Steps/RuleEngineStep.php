<?php

declare(strict_types=1);

namespace App\Domain\Pipelines\Steps;

use App\Domain\Pipelines\PipelineStep;
use App\Domain\Pipelines\RiskContext;
use App\Domain\Rules\RuleEngine;

final class RuleEngineStep implements PipelineStep
{
    public function __construct(private RuleEngine $ruleEngine)
    {
    }

    public function process(RiskContext $context): RiskContext
    {
        $ruleResult = $this->ruleEngine->evaluate($context);
        $context->setRuleResult($ruleResult);

        return $context;
    }
}
