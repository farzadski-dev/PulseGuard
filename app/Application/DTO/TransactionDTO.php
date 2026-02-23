<?php

declare(strict_types=1);

namespace App\Application\DTO;

use App\Domain\Entities\Transaction;
use App\Domain\ValueObjects\DeviceInfo;
use App\Domain\ValueObjects\GeoLocation;
use App\Domain\ValueObjects\Money;
use App\Domain\ValueObjects\TenantId;

final class TransactionDTO
{
    /** @param array<string, mixed> $metadata */
    public function __construct(
        public readonly string $tenantId,
        public readonly string $transactionId,
        public readonly ?string $idempotencyKey,
        public readonly int $amountMinor,
        public readonly string $currency,
        public readonly string $paymentMethod,
        public readonly array $device,
        public readonly array $location,
        public readonly string $occurredAt,
        public readonly array $metadata = []
    ) {
    }

    /** @param array<string, mixed> $payload */
    public static function fromArray(array $payload): self
    {
        return new self(
            $payload['tenant_id'],
            $payload['transaction_id'],
            $payload['idempotency_key'] ?? null,
            (int) $payload['amount'],
            strtoupper($payload['currency']),
            $payload['payment_method'],
            $payload['device'],
            $payload['location'],
            $payload['occurred_at'],
            $payload['metadata'] ?? []
        );
    }

    public function toDomain(): Transaction
    {
        return new Transaction(
            $this->transactionId,
            TenantId::fromString($this->tenantId),
            Money::fromMinor($this->amountMinor, $this->currency),
            $this->paymentMethod,
            new DeviceInfo(
                $this->device['device_id'],
                $this->device['ip'],
                $this->device['user_agent'] ?? null,
                $this->device['ip_country'] ?? null
            ),
            new GeoLocation(
                $this->location['country'],
                $this->location['region'] ?? null,
                $this->location['city'] ?? null,
                isset($this->location['lat']) ? (float) $this->location['lat'] : null,
                isset($this->location['lon']) ? (float) $this->location['lon'] : null
            ),
            new \DateTimeImmutable($this->occurredAt),
            $this->metadata,
            $this->idempotencyKey
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'tenant_id' => $this->tenantId,
            'transaction_id' => $this->transactionId,
            'idempotency_key' => $this->idempotencyKey,
            'amount' => $this->amountMinor,
            'currency' => $this->currency,
            'payment_method' => $this->paymentMethod,
            'device' => $this->device,
            'location' => $this->location,
            'occurred_at' => $this->occurredAt,
            'metadata' => $this->metadata,
        ];
    }
}
