<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Rules\RuleDefinition;
use App\Domain\ValueObjects\TenantId;

interface TenantRuleRepository
{
    /** @return array<int, RuleDefinition> */
    public function getRulesForTenant(TenantId $tenantId): array;
}
