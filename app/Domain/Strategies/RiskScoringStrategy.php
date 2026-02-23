<?php

declare(strict_types=1);

namespace App\Domain\Strategies;

use App\Domain\Entities\Transaction;
use App\Domain\Pipelines\RiskContext;

interface RiskScoringStrategy
{
    public function name(): string;

    /**
     * Returns a risk score between 0-1000.
     */
    public function score(Transaction $transaction, RiskContext $context): int;

    /**
     * @return array<int, string>
     */
    public function reasons(Transaction $transaction, RiskContext $context): array;
}
