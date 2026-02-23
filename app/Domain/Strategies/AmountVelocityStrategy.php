<?php

declare(strict_types=1);

namespace App\Domain\Strategies;

use App\Domain\Entities\Transaction;
use App\Domain\Pipelines\RiskContext;

final class AmountVelocityStrategy implements RiskScoringStrategy
{
    public function name(): string
    {
        return 'amount_velocity';
    }

    public function score(Transaction $transaction, RiskContext $context): int
    {
        $amount = $transaction->amount()->amountMinor();

        if ($amount >= 500000) { // $5,000.00 if scale=2
            return 650;
        }
        if ($amount >= 200000) {
            return 350;
        }
        if ($amount >= 50000) {
            return 150;
        }
        return 25;
    }

    public function reasons(Transaction $transaction, RiskContext $context): array
    {
        $amount = $transaction->amount()->amountMinor();
        if ($amount >= 500000) {
            return ['Very high transaction amount.'];
        }
        if ($amount >= 200000) {
            return ['High transaction amount.'];
        }
        if ($amount >= 50000) {
            return ['Elevated transaction amount.'];
        }
        return [];
    }
}
