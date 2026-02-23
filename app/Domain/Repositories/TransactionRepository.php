<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Entities\RiskResult;
use App\Domain\Entities\Transaction;

interface TransactionRepository
{
    public function save(Transaction $transaction, RiskResult $riskResult): void;
}
