<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Model;

final class RiskResultModel extends Model
{
    protected $table = 'risk_results';

    protected $guarded = [];

    protected $casts = [
        'strategy_scores' => 'array',
        'rule_adjustments' => 'array',
        'reasons' => 'array',
        'evaluated_at' => 'datetime',
    ];
}
