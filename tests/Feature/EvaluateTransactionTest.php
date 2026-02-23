<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

final class EvaluateTransactionTest extends TestCase
{
    public function test_evaluate_transaction_endpoint(): void
    {
        $payload = [
            'transaction_id' => 'txn_001',
            'amount' => 150000,
            'currency' => 'USD',
            'payment_method' => 'card',
            'device' => [
                'device_id' => 'device_1',
                'ip' => '203.0.113.10',
                'user_agent' => 'UA',
                'ip_country' => 'US',
            ],
            'location' => [
                'country' => 'US',
                'region' => 'CA',
                'city' => 'San Francisco',
                'lat' => 37.77,
                'lon' => -122.41,
            ],
            'occurred_at' => '2026-02-23T11:00:00Z',
            'metadata' => [
                'channel' => 'web',
            ],
        ];

        $response = $this->withHeaders([
            'X-Tenant-Id' => 'tenant_1',
            'Idempotency-Key' => 'idem-001',
        ])->postJson('/api/transactions/evaluate', $payload);

        $response->assertStatus(200)->assertJsonStructure([
            'transaction_id',
            'tenant_id',
            'risk_score',
            'risk_level',
            'reasons',
            'strategy_scores',
            'rule_adjustments',
            'evaluated_at',
        ]);
    }
}
