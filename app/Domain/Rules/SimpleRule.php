<?php

declare(strict_types=1);

namespace App\Domain\Rules;

use App\Domain\Pipelines\RiskContext;

final class SimpleRule implements Rule
{
    public function __construct(
        private string $name,
        private string $field,
        private string $operator,
        private string $value,
        private int $adjustment,
        private string $reason
    ) {
    }

    public function name(): string
    {
        return $this->name;
    }

    public function applies(RiskContext $context): bool
    {
        $left = $this->resolveField($context);

        return match ($this->operator) {
            'eq' => $left == $this->value,
            'neq' => $left != $this->value,
            'gt' => is_numeric($left) && $left > (float) $this->value,
            'gte' => is_numeric($left) && $left >= (float) $this->value,
            'lt' => is_numeric($left) && $left < (float) $this->value,
            'lte' => is_numeric($left) && $left <= (float) $this->value,
            'in' => in_array((string) $left, array_map('trim', explode(',', $this->value)), true),
            default => false,
        };
    }

    public function adjustment(): int
    {
        return $this->adjustment;
    }

    public function reason(): string
    {
        return $this->reason;
    }

    private function resolveField(RiskContext $context): mixed
    {
        $transaction = $context->transaction();

        return match ($this->field) {
            'amount_minor' => $transaction->amount()->amountMinor(),
            'currency' => $transaction->amount()->currency(),
            'country' => $transaction->location()->country(),
            'payment_method' => $transaction->paymentMethod(),
            'device_id' => $transaction->device()->deviceId(),
            'ip_country' => $transaction->device()->ipCountry(),
            default => $context->feature($this->field),
        };
    }
}
