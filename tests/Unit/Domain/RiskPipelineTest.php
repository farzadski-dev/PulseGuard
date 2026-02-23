<?php

declare(strict_types=1);

namespace Tests\Unit\Domain;

use App\Domain\Entities\Transaction;
use App\Domain\Pipelines\RiskContext;
use App\Domain\Pipelines\RiskPipeline;
use App\Domain\Pipelines\Steps\AggregationStep;
use App\Domain\Pipelines\Steps\EnrichmentStep;
use App\Domain\Pipelines\Steps\RuleEngineStep;
use App\Domain\Pipelines\Steps\StrategyScoringStep;
use App\Domain\Rules\SimpleRuleEngine;
use App\Domain\Strategies\AmountVelocityStrategy;
use App\Domain\Strategies\GeoDistanceStrategy;
use App\Domain\ValueObjects\DeviceInfo;
use App\Domain\ValueObjects\GeoLocation;
use App\Domain\ValueObjects\Money;
use App\Domain\ValueObjects\TenantId;
use PHPUnit\Framework\TestCase;

final class RiskPipelineTest extends TestCase
{
    public function test_pipeline_produces_result(): void
    {
        $transaction = new Transaction(
            'txn_123',
            TenantId::fromString('tenant_1'),
            Money::fromMinor(250000, 'USD'),
            'card',
            new DeviceInfo('device_1', '10.0.0.1', 'UA', 'US'),
            new GeoLocation('US', 'CA', 'San Francisco', 37.77, -122.41),
            new \DateTimeImmutable('now')
        );

        $pipeline = new RiskPipeline([
            new EnrichmentStep(),
            new StrategyScoringStep([new AmountVelocityStrategy(), new GeoDistanceStrategy()]),
            new RuleEngineStep(new SimpleRuleEngine([])),
            new AggregationStep(),
        ]);

        $context = $pipeline->handle(new RiskContext($transaction));

        $this->assertNotNull($context->result());
        $this->assertSame('medium', $context->result()?->level());
    }
}
