<?php

declare(strict_types=1);

namespace App\Domain\Strategies;

use App\Domain\Entities\Transaction;
use App\Domain\Pipelines\RiskContext;

final class GeoDistanceStrategy implements RiskScoringStrategy
{
    private const HIGH_RISK_COUNTRIES = ['IR', 'KP', 'SY', 'RU', 'NG'];

    public function name(): string
    {
        return 'geo_distance';
    }

    public function score(Transaction $transaction, RiskContext $context): int
    {
        $score = 0;
        $txnCountry = $transaction->location()->country();
        $ipCountry = $transaction->device()->ipCountry();

        if ($ipCountry !== null && $ipCountry !== $txnCountry) {
            $score += 200;
        }

        if (in_array($txnCountry, self::HIGH_RISK_COUNTRIES, true)) {
            $score += 300;
        }

        return min(1000, $score);
    }

    public function reasons(Transaction $transaction, RiskContext $context): array
    {
        $reasons = [];
        $txnCountry = $transaction->location()->country();
        $ipCountry = $transaction->device()->ipCountry();

        if ($ipCountry !== null && $ipCountry !== $txnCountry) {
            $reasons[] = 'IP country mismatch.';
        }
        if (in_array($txnCountry, self::HIGH_RISK_COUNTRIES, true)) {
            $reasons[] = 'Transaction originated from high-risk country.';
        }

        return $reasons;
    }
}
