<?php

declare(strict_types=1);

namespace App\Domain\ValueObjects;

final class TenantId
{
    public function __construct(private string $value)
    {
        if ($this->value === '') {
            throw new \InvalidArgumentException('TenantId cannot be empty.');
        }
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
