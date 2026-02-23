<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\Repositories\TenantRuleRepository;
use App\Domain\Rules\RuleDefinition;
use App\Domain\ValueObjects\TenantId;
use Illuminate\Support\Facades\DB;

final class MySqlTenantRuleRepository implements TenantRuleRepository
{
    public function getRulesForTenant(TenantId $tenantId): array
    {
        $rows = DB::table('tenant_rules')
            ->where('tenant_id', (string) $tenantId)
            ->where('enabled', true)
            ->orderByDesc('priority')
            ->get();

        $definitions = [];
        foreach ($rows as $row) {
            $definitions[] = new RuleDefinition(
                $row->name,
                $row->field,
                $row->operator,
                (string) $row->value,
                (int) $row->adjustment,
                $row->reason,
                (bool) $row->enabled,
                (int) $row->priority
            );
        }

        return $definitions;
    }
}
