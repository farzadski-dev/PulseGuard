<?php

declare(strict_types=1);

namespace App\Interfaces\Http\Resources;

use App\Application\DTO\RiskEvaluationResponseDTO;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin RiskEvaluationResponseDTO */
final class RiskEvaluationResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray($request): array
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
