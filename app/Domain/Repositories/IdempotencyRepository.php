<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

interface IdempotencyRepository
{
    /** @return array<string, mixed>|null */
    public function get(string $key): ?array;

    /** @param array<string, mixed> $payload */
    public function put(string $key, array $payload, int $ttlSeconds): void;
}
