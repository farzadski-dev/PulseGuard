<?php

declare(strict_types=1);

namespace App\Infrastructure\Queue;

use App\Application\DTO\RiskEvaluationResponseDTO;
use App\Application\DTO\TransactionDTO;
use App\Application\Services\AsyncTransactionStore;
use App\Infrastructure\Queue\Jobs\StoreTransactionJob;
use Illuminate\Contracts\Bus\Dispatcher;

final class QueuedTransactionStore implements AsyncTransactionStore
{
    public function __construct(private Dispatcher $dispatcher)
    {
    }

    public function store(TransactionDTO $transaction, RiskEvaluationResponseDTO $riskResult): void
    {
        $this->dispatcher->dispatch(new StoreTransactionJob(
            $transaction->toArray(),
            $riskResult->toArray()
        ));
    }
}
