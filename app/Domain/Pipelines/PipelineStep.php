<?php

declare(strict_types=1);

namespace App\Domain\Pipelines;

interface PipelineStep
{
    public function process(RiskContext $context): RiskContext;
}
