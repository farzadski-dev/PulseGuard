<?php

declare(strict_types=1);

namespace App\Domain\Pipelines;

final class RiskPipeline
{
    /** @param array<int, PipelineStep> $steps */
    public function __construct(private array $steps)
    {
    }

    public function handle(RiskContext $context): RiskContext
    {
        foreach ($this->steps as $step) {
            $context = $step->process($context);
        }

        return $context;
    }
}
