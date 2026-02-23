<?php

declare(strict_types=1);

namespace App\Domain\ValueObjects;

final class RiskScore
{
    public function __construct(private int $value)
    {
        $this->value = max(0, min(1000, $this->value));
    }

    public function value(): int
    {
        return $this->value;
    }

    public function level(): string
    {
        if ($this->value >= 700) {
            return 'high';
        }
        if ($this->value >= 300) {
            return 'medium';
        }
        return 'low';
    }
}
