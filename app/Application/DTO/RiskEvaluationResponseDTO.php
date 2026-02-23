<?php

declare(strict_types=1);

namespace App\Application\DTO;

use App\Domain\Entities\RiskResult;
use App\Domain\ValueObjects\RiskScore;
use App\Domain\ValueObjects\TenantId;

final class RiskEvaluationResponseDTO
{
    /**
     * @param array<int, string> $reasons
     * @param array<string, int> $strategyScores
     * @param array<string, int> $ruleAdjustments
     */
    public function __construct(
        public readonly string $transactionId,
        public readonly string $tenantId,
        public readonly int $riskScore,
        public readonly string $riskLevel,
        public readonly array $reasons,
        public readonly array $strategyScores,
        public readonly array $ruleAdjustments,
        public readonly string $evaluatedAt
    ) {
    }

    public static function fromRiskResult(RiskResult $result): self
    {
        return new self(
            $result->transactionId(),
            (string) $result->tenantId(),
            $result->score()->value(),
            $result->level(),
            $result->reasons(),
            $result->strategyScores(),
            $result->ruleAdjustments(),
            $result->evaluatedAt()->format(DATE_ATOM)
        );
    }

    /** @param array<string, mixed> $payload */
    public static function fromArray(array $payload): self
    {
        return new self(
            $payload['transaction_id'],
            $payload['tenant_id'],
            (int) $payload['risk_score'],
            $payload['risk_level'],
            $payload['reasons'] ?? [],
            $payload['strategy_scores'] ?? [],
            $payload['rule_adjustments'] ?? [],
            $payload['evaluated_at']
        );
    }

    public function toDomain(): RiskResult
    {
        return new RiskResult(
            $this->transactionId,
            TenantId::fromString($this->tenantId),
            new RiskScore($this->riskScore),
            $this->riskLevel,
            $this->strategyScores,
            $this->ruleAdjustments,
            $this->reasons,
            new \DateTimeImmutable($this->evaluatedAt)
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'transaction_id' => $this->transactionId,
            'tenant_id' => $this->tenantId,
            'risk_score' => $this->riskScore,
            'risk_level' => $this->riskLevel,
            'reasons' => $this->reasons,
            'strategy_scores' => $this->strategyScores,
            'rule_adjustments' => $this->ruleAdjustments,
            'evaluated_at' => $this->evaluatedAt,
        ];
    }
}
