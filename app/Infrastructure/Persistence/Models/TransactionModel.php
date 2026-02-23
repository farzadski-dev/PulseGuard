<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Model;

final class TransactionModel extends Model
{
    protected $table = 'transactions';

    protected $guarded = [];

    protected $casts = [
        'device' => 'array',
        'location' => 'array',
        'metadata' => 'array',
        'occurred_at' => 'datetime',
    ];
}
