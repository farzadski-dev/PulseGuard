<?php

declare(strict_types=1);

namespace App\Infrastructure\Cache;

use App\Domain\Repositories\IdempotencyRepository;
use Illuminate\Support\Facades\Redis;

final class RedisIdempotencyRepository implements IdempotencyRepository
{
    public function get(string $key): ?array
    {
        $payload = Redis::get($this->key($key));
        if ($payload === null) {
            return null;
        }

        /** @var array<string, mixed> $decoded */
        $decoded = json_decode($payload, true, 512, JSON_THROW_ON_ERROR);
        return $decoded;
    }

    public function put(string $key, array $payload, int $ttlSeconds): void
    {
        Redis::setex(
            $this->key($key),
            $ttlSeconds,
            json_encode($payload, JSON_THROW_ON_ERROR)
        );
    }

    private function key(string $key): string
    {
        return 'idempotency:' . $key;
    }
}
