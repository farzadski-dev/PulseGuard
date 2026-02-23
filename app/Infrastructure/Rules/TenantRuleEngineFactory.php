<?php

declare(strict_types=1);

namespace App\Infrastructure\Rules;

use App\Domain\Repositories\TenantRuleRepository;
use App\Domain\Rules\RuleEngine;
use App\Domain\Rules\RuleEngineFactory;
use App\Domain\Rules\SimpleRule;
use App\Domain\Rules\SimpleRuleEngine;
use App\Domain\ValueObjects\TenantId;
use Illuminate\Support\Facades\Cache;

final class TenantRuleEngineFactory implements RuleEngineFactory
{
    public function __construct(private TenantRuleRepository $tenantRuleRepository)
    {
    }

    public function forTenant(TenantId $tenantId): RuleEngine
    {
        $definitions = Cache::remember(
            'tenant_rules:' . (string) $tenantId,
            now()->addMinutes(2),
            fn () => $this->tenantRuleRepository->getRulesForTenant($tenantId)
        );

        $rules = [];
        foreach ($definitions as $definition) {
            if (!$definition->enabled) {
                continue;
            }

            $rules[] = new SimpleRule(
                $definition->name,
                $definition->field,
                $definition->operator,
                $definition->value,
                $definition->adjustment,
                $definition->reason
            );
        }

        return new SimpleRuleEngine($rules);
    }
}
