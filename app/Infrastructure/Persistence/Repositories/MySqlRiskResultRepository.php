<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\Entities\RiskResult;
use App\Domain\Repositories\RiskResultRepository;
use Illuminate\Support\Facades\DB;

final class MySqlRiskResultRepository implements RiskResultRepository
{
    public function save(RiskResult $riskResult): void
    {
        DB::table('risk_results')->insertOrIgnore([
            'tenant_id' => (string) $riskResult->tenantId(),
            'transaction_id' => $riskResult->transactionId(),
            'score' => $riskResult->score()->value(),
            'level' => $riskResult->level(),
            'strategy_scores' => json_encode($riskResult->strategyScores(), JSON_THROW_ON_ERROR),
            'rule_adjustments' => json_encode($riskResult->ruleAdjustments(), JSON_THROW_ON_ERROR),
            'reasons' => json_encode($riskResult->reasons(), JSON_THROW_ON_ERROR),
            'evaluated_at' => $riskResult->evaluatedAt()->format('Y-m-d H:i:s'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
