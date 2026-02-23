<?php

declare(strict_types=1);

namespace App\Domain\ValueObjects;

final class GeoLocation
{
    public function __construct(
        private string $country,
        private ?string $region = null,
        private ?string $city = null,
        private ?float $latitude = null,
        private ?float $longitude = null
    ) {
        if (!preg_match('/^[A-Z]{2}$/', $this->country)) {
            throw new \InvalidArgumentException('Country must be ISO-3166 alpha-2.');
        }
    }

    public function country(): string
    {
        return $this->country;
    }

    public function region(): ?string
    {
        return $this->region;
    }

    public function city(): ?string
    {
        return $this->city;
    }

    public function latitude(): ?float
    {
        return $this->latitude;
    }

    public function longitude(): ?float
    {
        return $this->longitude;
    }
}
