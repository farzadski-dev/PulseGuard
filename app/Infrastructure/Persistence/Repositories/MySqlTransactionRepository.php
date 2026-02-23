<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\Entities\RiskResult;
use App\Domain\Entities\Transaction;
use App\Domain\Repositories\TransactionRepository;
use Illuminate\Support\Facades\DB;

final class MySqlTransactionRepository implements TransactionRepository
{
    public function save(Transaction $transaction, RiskResult $riskResult): void
    {
        DB::table('transactions')->insertOrIgnore([
            'tenant_id' => (string) $transaction->tenantId(),
            'transaction_id' => $transaction->transactionId(),
            'idempotency_key' => $transaction->idempotencyKey(),
            'amount_minor' => $transaction->amount()->amountMinor(),
            'currency' => $transaction->amount()->currency(),
            'payment_method' => $transaction->paymentMethod(),
            'device' => json_encode([
                'device_id' => $transaction->device()->deviceId(),
                'ip' => $transaction->device()->ipAddress(),
                'user_agent' => $transaction->device()->userAgent(),
                'ip_country' => $transaction->device()->ipCountry(),
            ], JSON_THROW_ON_ERROR),
            'location' => json_encode([
                'country' => $transaction->location()->country(),
                'region' => $transaction->location()->region(),
                'city' => $transaction->location()->city(),
                'lat' => $transaction->location()->latitude(),
                'lon' => $transaction->location()->longitude(),
            ], JSON_THROW_ON_ERROR),
            'metadata' => json_encode($transaction->metadata(), JSON_THROW_ON_ERROR),
            'occurred_at' => $transaction->occurredAt()->format('Y-m-d H:i:s'),
            'risk_score' => $riskResult->score()->value(),
            'risk_level' => $riskResult->level(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
