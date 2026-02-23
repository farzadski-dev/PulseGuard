<?php

declare(strict_types=1);

namespace App\Domain\Rules;

use App\Domain\Pipelines\RiskContext;

final class SimpleRuleEngine implements RuleEngine
{
    /** @param array<int, Rule> $rules */
    public function __construct(private array $rules)
    {
    }

    public function evaluate(RiskContext $context): RuleResult
    {
        $adjustments = [];
        $total = 0;

        foreach ($this->rules as $rule) {
            if (!$rule->applies($context)) {
                continue;
            }
            $adjustments[$rule->name()] = $rule->adjustment();
            $total += $rule->adjustment();
            $context->addReason($rule->reason());
        }

        return new RuleResult($adjustments, $total);
    }
}
