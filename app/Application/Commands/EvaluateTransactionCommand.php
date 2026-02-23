<?php

declare(strict_types=1);

namespace App\Application\Commands;

use App\Application\DTO\TransactionDTO;

final class EvaluateTransactionCommand
{
    public function __construct(
        public readonly TransactionDTO $transaction,
        public readonly ?string $idempotencyKey,
        public readonly string $requestId
    ) {
    }
}
