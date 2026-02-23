<?php

declare(strict_types=1);

namespace App\Domain\Rules;

use App\Domain\ValueObjects\TenantId;

interface RuleEngineFactory
{
    public function forTenant(TenantId $tenantId): RuleEngine;
}
