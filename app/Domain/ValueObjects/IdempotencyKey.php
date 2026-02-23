<?php

declare(strict_types=1);

namespace App\Domain\ValueObjects;

final class IdempotencyKey
{
    public function __construct(private string $value)
    {
        if ($this->value === '') {
            throw new \InvalidArgumentException('IdempotencyKey cannot be empty.');
        }
        if (strlen($this->value) > 64) {
            throw new \InvalidArgumentException('IdempotencyKey too long.');
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

    public function __toString(): string
    {
        return $this->value;
    }
}
