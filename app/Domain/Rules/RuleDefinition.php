<?php

declare(strict_types=1);

namespace App\Domain\Rules;

final class RuleDefinition
{
    public function __construct(
        public readonly string $name,
        public readonly string $field,
        public readonly string $operator,
        public readonly string $value,
        public readonly int $adjustment,
        public readonly string $reason,
        public readonly bool $enabled,
        public readonly int $priority
    ) {
    }
}
