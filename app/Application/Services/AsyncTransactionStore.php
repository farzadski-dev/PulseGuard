<?php

declare(strict_types=1);

namespace App\Application\Services;

use App\Application\DTO\RiskEvaluationResponseDTO;
use App\Application\DTO\TransactionDTO;

interface AsyncTransactionStore
{
    public function store(TransactionDTO $transaction, RiskEvaluationResponseDTO $riskResult): void;
}
