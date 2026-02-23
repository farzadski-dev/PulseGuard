<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapters;

use App\Domain\Services\DeviceReputationService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

final class DeviceReputationHttpAdapter implements DeviceReputationService
{
    public function getReputationScore(string $deviceId, string $ipAddress): int
    {
        $cacheKey = 'device_rep:' . sha1($deviceId . '|' . $ipAddress);

        return (int) Cache::remember($cacheKey, now()->addMinutes(5), function () use ($deviceId, $ipAddress) {
            $response = Http::timeout(0.4)
                ->retry(1, 50)
                ->asJson()
                ->post(config('services.device_reputation.url'), [
                    'device_id' => $deviceId,
                    'ip' => $ipAddress,
                ]);

            if (!$response->ok()) {
                return 70; // fail-open baseline
            }

            return (int) ($response->json('score') ?? 70);
        });
    }
}
