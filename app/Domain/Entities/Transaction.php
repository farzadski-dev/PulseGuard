<?php

declare(strict_types=1);

namespace App\Domain\Entities;

use App\Domain\ValueObjects\DeviceInfo;
use App\Domain\ValueObjects\GeoLocation;
use App\Domain\ValueObjects\Money;
use App\Domain\ValueObjects\TenantId;

final class Transaction
{
    /** @param array<string, mixed> $metadata */
    public function __construct(
        private string $transactionId,
        private TenantId $tenantId,
        private Money $amount,
        private string $paymentMethod,
        private DeviceInfo $device,
        private GeoLocation $location,
        private \DateTimeImmutable $occurredAt,
        private array $metadata = [],
        private ?string $idempotencyKey = null
    ) {
        if ($this->transactionId === '') {
            throw new \InvalidArgumentException('Transaction ID cannot be empty.');
        }
        if ($this->paymentMethod === '') {
            throw new \InvalidArgumentException('Payment method cannot be empty.');
        }
    }

    public function transactionId(): string
    {
        return $this->transactionId;
    }

    public function tenantId(): TenantId
    {
        return $this->tenantId;
    }

    public function amount(): Money
    {
        return $this->amount;
    }

    public function paymentMethod(): string
    {
        return $this->paymentMethod;
    }

    public function device(): DeviceInfo
    {
        return $this->device;
    }

    public function location(): GeoLocation
    {
        return $this->location;
    }

    public function occurredAt(): \DateTimeImmutable
    {
        return $this->occurredAt;
    }

    /** @return array<string, mixed> */
    public function metadata(): array
    {
        return $this->metadata;
    }

    public function idempotencyKey(): ?string
    {
        return $this->idempotencyKey;
    }
}
