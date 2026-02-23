<?php

declare(strict_types=1);

namespace App\Domain\Rules;

final class RuleResult
{
    /** @param array<string, int> $adjustments */
    public function __construct(private array $adjustments, private int $totalAdjustment)
    {
    }

    /** @return array<string, int> */
    public function adjustments(): array
    {
        return $this->adjustments;
    }

    public function totalAdjustment(): int
    {
        return $this->totalAdjustment;
    }
}
