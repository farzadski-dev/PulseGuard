<?php

declare(strict_types=1);

namespace App\Domain\ValueObjects;

final class DeviceInfo
{
    public function __construct(
        private string $deviceId,
        private string $ipAddress,
        private ?string $userAgent = null,
        private ?string $ipCountry = null
    ) {
        if ($this->deviceId === '') {
            throw new \InvalidArgumentException('Device ID cannot be empty.');
        }
        if ($this->ipAddress === '') {
            throw new \InvalidArgumentException('IP address cannot be empty.');
        }
    }

    public function deviceId(): string
    {
        return $this->deviceId;
    }

    public function ipAddress(): string
    {
        return $this->ipAddress;
    }

    public function userAgent(): ?string
    {
        return $this->userAgent;
    }

    public function ipCountry(): ?string
    {
        return $this->ipCountry;
    }
}
