<?php

declare(strict_types=1);

namespace App\Infrastructure\Queue\Jobs;

use App\Application\DTO\RiskEvaluationResponseDTO;
use App\Application\DTO\TransactionDTO;
use App\Domain\Repositories\RiskResultRepository;
use App\Domain\Repositories\TransactionRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class StoreTransactionJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /** @param array<string, mixed> $transaction */
    /** @param array<string, mixed> $riskResult */
    public function __construct(
        private array $transaction,
        private array $riskResult
    ) {
    }

    public function handle(TransactionRepository $transactionRepository, RiskResultRepository $riskResultRepository): void
    {
        $transactionDto = TransactionDTO::fromArray($this->transaction);
        $riskDto = RiskEvaluationResponseDTO::fromArray($this->riskResult);

        $transaction = $transactionDto->toDomain();
        $riskResult = $riskDto->toDomain();

        $transactionRepository->save($transaction, $riskResult);
        $riskResultRepository->save($riskResult);
    }
}
