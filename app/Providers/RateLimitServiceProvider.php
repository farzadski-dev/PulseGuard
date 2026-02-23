<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

final class RateLimitServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        RateLimiter::for('transactions', function (Request $request) {
            $tenantId = $request->header('X-Tenant-Id')
                ?? $request->input('tenant_id')
                ?? $request->ip();

            return Limit::perMinute(1200)->by($tenantId);
        });
    }
}
