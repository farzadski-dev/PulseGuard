<?php

declare(strict_types=1);

namespace App\Domain\ValueObjects;

final class Money
{
    public function __construct(private int $amountMinor, private string $currency)
    {
        if ($this->amountMinor < 0) {
            throw new \InvalidArgumentException('Amount must be non-negative.');
        }
        if (!preg_match('/^[A-Z]{3}$/', $this->currency)) {
            throw new \InvalidArgumentException('Currency must be ISO-4217 (3 uppercase letters).');
        }
    }

    public static function fromMinor(int $amountMinor, string $currency): self
    {
        return new self($amountMinor, $currency);
    }

    public static function fromFloat(float $amount, string $currency, int $scale = 2): self
    {
        $minor = (int) round($amount * (10 ** $scale));
        return new self($minor, $currency);
    }

    public function amountMinor(): int
    {
        return $this->amountMinor;
    }

    public function currency(): string
    {
        return $this->currency;
    }
}
