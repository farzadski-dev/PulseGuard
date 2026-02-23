<?php

declare(strict_types=1);

namespace App\Domain\Rules;

use App\Domain\Pipelines\RiskContext;

interface RuleEngine
{
    public function evaluate(RiskContext $context): RuleResult;
}
