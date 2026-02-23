<?php

declare(strict_types=1);

namespace App\Domain\Entities;

use App\Domain\ValueObjects\RiskScore;
use App\Domain\ValueObjects\TenantId;

final class RiskResult
{
    /**
     * @param array<string, int> $strategyScores
     * @param array<string, int> $ruleAdjustments
     * @param array<int, string> $reasons
     */
    public function __construct(
        private string $transactionId,
        private TenantId $tenantId,
        private RiskScore $score,
        private string $level,
        private array $strategyScores,
        private array $ruleAdjustments,
        private array $reasons,
        private \DateTimeImmutable $evaluatedAt
    ) {
        if ($this->transactionId === '') {
            throw new \InvalidArgumentException('Transaction ID cannot be empty.');
        }
    }

    public function transactionId(): string
    {
        return $this->transactionId;
    }

    public function tenantId(): TenantId
    {
        return $this->tenantId;
    }

    public function score(): RiskScore
    {
        return $this->score;
    }

    public function level(): string
    {
        return $this->level;
    }

    /** @return array<string, int> */
    public function strategyScores(): array
    {
        return $this->strategyScores;
    }

    /** @return array<string, int> */
    public function ruleAdjustments(): array
    {
        return $this->ruleAdjustments;
    }

    /** @return array<int, string> */
    public function reasons(): array
    {
        return $this->reasons;
    }

    public function evaluatedAt(): \DateTimeImmutable
    {
        return $this->evaluatedAt;
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'transaction_id' => $this->transactionId,
            'tenant_id' => (string) $this->tenantId,
            'risk_score' => $this->score->value(),
            'risk_level' => $this->level,
            'strategy_scores' => $this->strategyScores,
            'rule_adjustments' => $this->ruleAdjustments,
            'reasons' => $this->reasons,
            'evaluated_at' => $this->evaluatedAt->format(DATE_ATOM),
        ];
    }
}
