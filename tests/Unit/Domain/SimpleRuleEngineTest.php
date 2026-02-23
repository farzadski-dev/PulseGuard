<?php

declare(strict_types=1);

namespace Tests\Unit\Domain;

use App\Domain\Entities\Transaction;
use App\Domain\Pipelines\RiskContext;
use App\Domain\Rules\SimpleRule;
use App\Domain\Rules\SimpleRuleEngine;
use App\Domain\ValueObjects\DeviceInfo;
use App\Domain\ValueObjects\GeoLocation;
use App\Domain\ValueObjects\Money;
use App\Domain\ValueObjects\TenantId;
use PHPUnit\Framework\TestCase;

final class SimpleRuleEngineTest extends TestCase
{
    public function test_rule_engine_applies_rule(): void
    {
        $transaction = new Transaction(
            'txn_456',
            TenantId::fromString('tenant_1'),
            Money::fromMinor(1000000, 'USD'),
            'card',
            new DeviceInfo('device_2', '10.0.0.2', null, 'US'),
            new GeoLocation('US'),
            new \DateTimeImmutable('now')
        );

        $context = new RiskContext($transaction);

        $engine = new SimpleRuleEngine([
            new SimpleRule('high_amount', 'amount_minor', 'gt', '500000', 200, 'High amount rule triggered.')
        ]);

        $result = $engine->evaluate($context);

        $this->assertSame(200, $result->totalAdjustment());
        $this->assertArrayHasKey('high_amount', $result->adjustments());
    }
}
