<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Model;

final class TenantRuleModel extends Model
{
    protected $table = 'tenant_rules';

    protected $guarded = [];

    protected $casts = [
        'enabled' => 'bool',
        'priority' => 'int',
        'adjustment' => 'int',
    ];
}
