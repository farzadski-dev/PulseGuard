<?php

declare(strict_types=1);

namespace App\Domain\Pipelines\Steps;

use App\Domain\Pipelines\PipelineStep;
use App\Domain\Pipelines\RiskContext;

final class EnrichmentStep implements PipelineStep
{
    public function process(RiskContext $context): RiskContext
    {
        $transaction = $context->transaction();

        $context->addFeature('amount_minor', $transaction->amount()->amountMinor());
        $context->addFeature('currency', $transaction->amount()->currency());
        $context->addFeature('payment_method', $transaction->paymentMethod());
        $context->addFeature('country', $transaction->location()->country());

        $hour = (int) $transaction->occurredAt()->format('H');
        $context->addFeature('transaction_hour', $hour);
        $context->addFeature('is_night_time', $hour < 6 || $hour > 22);

        return $context;
    }
}
