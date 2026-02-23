<?php

declare(strict_types=1);

namespace App\Providers;

use App\Application\Services\AsyncTransactionStore;
use App\Application\Services\EvaluateTransactionService;
use App\Domain\Repositories\IdempotencyRepository;
use App\Domain\Repositories\RiskResultRepository;
use App\Domain\Repositories\TenantRuleRepository;
use App\Domain\Repositories\TransactionRepository;
use App\Domain\Rules\RuleEngineFactory;
use App\Domain\Services\DeviceReputationService;
use App\Domain\Strategies\AmountVelocityStrategy;
use App\Domain\Strategies\DeviceReputationStrategy;
use App\Domain\Strategies\GeoDistanceStrategy;
use App\Infrastructure\Adapters\DeviceReputationHttpAdapter;
use App\Infrastructure\Cache\RedisIdempotencyRepository;
use App\Infrastructure\Persistence\Repositories\MySqlRiskResultRepository;
use App\Infrastructure\Persistence\Repositories\MySqlTenantRuleRepository;
use App\Infrastructure\Persistence\Repositories\MySqlTransactionRepository;
use App\Infrastructure\Queue\QueuedTransactionStore;
use App\Infrastructure\Rules\TenantRuleEngineFactory;
use Illuminate\Support\ServiceProvider;

final class RiskServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(IdempotencyRepository::class, RedisIdempotencyRepository::class);
        $this->app->bind(TransactionRepository::class, MySqlTransactionRepository::class);
        $this->app->bind(RiskResultRepository::class, MySqlRiskResultRepository::class);
        $this->app->bind(TenantRuleRepository::class, MySqlTenantRuleRepository::class);

        $this->app->bind(DeviceReputationService::class, DeviceReputationHttpAdapter::class);
        $this->app->bind(RuleEngineFactory::class, TenantRuleEngineFactory::class);
        $this->app->bind(AsyncTransactionStore::class, QueuedTransactionStore::class);

        $this->app->tag([
            AmountVelocityStrategy::class,
            GeoDistanceStrategy::class,
            DeviceReputationStrategy::class,
        ], 'risk.strategies');

        $this->app->when(EvaluateTransactionService::class)
            ->needs('$strategies')
            ->giveTagged('risk.strategies');
    }
}
